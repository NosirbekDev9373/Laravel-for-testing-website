<?php

namespace App\Http\Controllers;

use App\Events\PostCreated as PostCreatedEvent;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use App\Notifications\PostDeletedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    use AuthorizesRequests;

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');
        $canManageAllPosts = $isAdmin || $user->hasRole('editor');

        $postsQuery = Post::query()
            ->when(! $canManageAllPosts, fn ($query) => $query->where('user_id', $user->id));

        return view('admin.dashboard', [
            'isAdmin' => $isAdmin,
            'usersCount' => $isAdmin ? User::count() : 0,
            'postsCount' => (clone $postsQuery)->count(),
            'categoriesCount' => $isAdmin ? Category::count() : 0,
            'tagsCount' => $isAdmin ? Tag::count() : 0,
            'commentsCount' => $isAdmin ? Comment::count() : 0,
            'unreadContactsCount' => $isAdmin ? ContactMessage::whereNull('read_at')->count() : 0,
            'latestPosts' => $postsQuery->with(['user', 'category'])->latest()->take(8)->get(),
            'latestUsers' => $isAdmin ? User::latest()->take(8)->get() : collect(),
        ]);
    }

    public function posts(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $user = $request->user();
        $canManageAllPosts = $user->hasRole('admin') || $user->hasRole('editor');

        $categories = Category::orderBy('name')->get();

        $categoryId = $request->query('category');
        $categoryId = $categoryId !== null ? filter_var($categoryId, FILTER_VALIDATE_INT) : null;
        $categoryId = $categoryId && $categories->contains('id', $categoryId) ? $categoryId : null;

        $search = trim($request->string('search')->toString());

        $posts = Post::with(['user', 'category'])
            ->when(! $canManageAllPosts, fn ($query) => $query->where('user_id', $user->id))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(title) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(short_content) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(content) LIKE ?', [$like])
                        ->orWhereHas('user', function ($query) use ($like) {
                            $query->whereRaw('LOWER(name) LIKE ?', [$like])
                                ->orWhereRaw('LOWER(email) LIKE ?', [$like]);
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'categories', 'search', 'categoryId'));
    }

    public function createPost()
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.form', [
            'post' => new Post,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'editing' => false,
        ]);
    }

    public function storePost(Request $request)
    {
        $this->authorize('create', Post::class);

        $data = $this->validatePost($request, true);

        unset($data['remove_image'], $data['remove_image_hover'], $data['remove_gallery']);

        $data['user_id'] = $request->user()->id;
        $data['image'] = $request->hasFile('image')
            ? $request->file('image')->store('post-images', 'public')
            : null;
        $data['image_hover'] = $request->hasFile('image_hover')
            ? $request->file('image_hover')->store('post-images', 'public')
            : null;

        $gallery = [];
        foreach ($request->file('gallery_images', []) as $file) {
            $gallery[] = $file->store('post-images', 'public');
        }
        $data['gallery_images'] = $gallery ?: null;

        $post = Post::create($data);
        $post->tags()->sync($request->input('tags', []));

        PostCreatedEvent::dispatch($post);
        $request->user()->notify(new PostCreatedNotification($post->title, $post->id));

        return redirect()->route('admin.posts.index')->with('success', 'Post yaratildi.');
    }

    public function editPost(Post $post)
    {
        $this->authorize('update', $post);

        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'editing' => true,
        ]);
    }

    public function updatePost(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $this->validatePost($request, false);

        if ($request->hasFile('gallery_images')) {
            $currentGallery = $post->gallery_images ?? [];
            $removeGallery = array_intersect($currentGallery, (array) $request->input('remove_gallery', []));
            $remainingImages = count($currentGallery) - count($removeGallery);

            if ($remainingImages + count($request->file('gallery_images')) > 4) {
                throw ValidationException::withMessages([
                    'gallery_images' => 'Galereyada jami 4 tadan ko‘p rasm bo‘lishi mumkin emas.',
                ]);
            }
        }

        unset($data['remove_image'], $data['remove_image_hover'], $data['remove_gallery']);

        foreach (['image' => 'image', 'image_hover' => 'image_hover'] as $input => $column) {
            if ($request->hasFile($input)) {
                if ($post->{$column}) {
                    Storage::disk('public')->delete($post->{$column});
                }
                $data[$column] = $request->file($input)->store('post-images', 'public');
            } elseif ($request->boolean('remove_'.$column) && $post->{$column}) {
                Storage::disk('public')->delete($post->{$column});
                $data[$column] = null;
            }
        }

        $galleryChanged = $request->has('remove_gallery') || $request->hasFile('gallery_images');

        if ($galleryChanged) {
            $gallery = $post->gallery_images ?? [];

            $removeGallery = array_intersect($gallery, (array) $request->input('remove_gallery', []));

            foreach ($removeGallery as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $gallery = array_values(array_diff($gallery, $removeGallery));

            foreach ($request->file('gallery_images', []) as $file) {
                $gallery[] = $file->store('post-images', 'public');
            }

            $data['gallery_images'] = $gallery ?: null;
        }

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.posts.index')->with('success', 'Post yangilandi.');
    }

    public function destroyPost(Post $post)
    {
        $this->authorize('delete', $post);

        foreach ([$post->image, $post->image_hover] as $image) {
            if ($image) {
                Storage::disk('public')->delete($image);
            }
        }

        foreach (($post->gallery_images ?? []) as $image) {
            Storage::disk('public')->delete($image);
        }

        $owner = $post->user;
        $post->delete();

        $owner?->notify(new PostDeletedNotification($post->title));

        return redirect()->route('admin.posts.index')->with('success', 'Post o‘chirildi.');
    }

    private function validatePost(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'short_content' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'image' => [$creating ? 'required' : 'sometimes', 'image', 'max:5120'],
            'image_hover' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array', 'max:4'],
            'gallery_images.*' => ['image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'remove_image_hover' => ['nullable', 'boolean'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ], [
            'gallery_images.max' => 'Galereya rasmlari 4 tadan oshmasligi kerak.',
        ]);

        $data['duration'] = $data['duration'] ?? 0;

        return $data;
    }

    public function users(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        $search = trim($request->string('search')->toString());

        $roleId = $request->query('role');
        $roleId = $roleId !== null ? filter_var($roleId, FILTER_VALIDATE_INT) : null;
        $roleId = $roleId && $roles->contains('id', $roleId) ? $roleId : null;

        $users = User::with('roles')
            ->withCount(['posts', 'comments'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$like]);
                });
            })
            ->when($roleId, fn ($query) => $query->whereHas('roles', fn ($query) => $query->whereKey($roleId)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'roleId' => $roleId,
        ]);
    }

    public function updateUserRole(Request $request, User $user)
    {
        if ($user->is($request->user()) && ! $request->input('roles')) {
            return back()->withErrors(['roles' => 'O‘zingizning admin rolingizni olib tashlay olmaysiz.']);
        }

        $validated = $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'distinct', 'exists:roles,id'],
        ]);

        if ($user->is($request->user())) {
            $adminRoleId = Role::where('name', 'admin')->value('id');
            if (! $adminRoleId || ! in_array($adminRoleId, $validated['roles'])) {
                return back()->withErrors(['roles' => 'O‘zingizdan admin huquqini olib tashlay olmaysiz.']);
            }
        }

        $user->roles()->sync($validated['roles']);

        return back()->with('success', 'Foydalanuvchi rollari yangilandi.');
    }

    public function destroyUser(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'O‘zingizning hisobingizni bu sahifadan o‘chira olmaysiz.']);
        }

        if ($user->posts()->exists() || $user->comments()->exists()) {
            return back()->withErrors([
                'user' => 'Bu foydalanuvchining postlari yoki izohlari bor. Avval ularni boshqaring, keyin foydalanuvchini o‘chiring.',
            ]);
        }

        $user->roles()->detach();
        $user->delete();

        return back()->with('success', 'Foydalanuvchi o‘chirildi.');
    }

    public function comments(Request $request)
    {
        $search = trim($request->string('search')->toString());

        $comments = Comment::with(['user', 'post'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(body) LIKE ?', [$like])
                        ->orWhereHas('user', function ($query) use ($like) {
                            $query->whereRaw('LOWER(name) LIKE ?', [$like])
                                ->orWhereRaw('LOWER(email) LIKE ?', [$like]);
                        })
                        ->orWhereHas('post', function ($query) use ($like) {
                            $query->whereRaw('LOWER(title) LIKE ?', [$like]);
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.comments.index', [
            'comments' => $comments,
            'search' => $search,
        ]);
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Izoh o‘chirildi.');
    }

    public function contacts(Request $request)
    {
        $search = trim($request->string('search')->toString());
        $status = $request->query('status');
        $status = in_array($status, ['read', 'unread'], true) ? $status : null;

        $contacts = ContactMessage::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.Str::lower($search).'%';

                $query->where(function ($query) use ($like) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(service_type) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(message) LIKE ?', [$like]);
                });
            })
            ->when($status === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->when($status === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'search' => $search,
            'status' => $status,
            'unreadCount' => ContactMessage::whereNull('read_at')->count(),
        ]);
    }

    public function showContact(ContactMessage $contactMessage)
    {
        if (! $contactMessage->read_at) {
            $contactMessage->forceFill(['read_at' => now()])->save();
        }

        return view('admin.contacts.show', ['contact' => $contactMessage]);
    }

    public function destroyContact(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Xabar o‘chirildi.');
    }

    public function categories(Request $request)
    {
        $search = trim($request->string('search')->toString());

        $items = Category::withCount('post')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.Str::lower($search).'%']);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.taxonomy.index', [
            'pageTitle' => 'Kategoriyalar',
            'items' => $items,
            'indexRoute' => 'admin.categories.index',
            'storeRoute' => 'admin.categories.store',
            'updateRoute' => 'admin.categories.update',
            'deleteRoute' => 'admin.categories.destroy',
            'search' => $search,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $request->merge(['name' => Str::title(trim((string) $request->input('name')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);
        Category::create($data);

        return back()->with('success', 'Kategoriya yaratildi.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->merge(['name' => Str::title(trim((string) $request->input('name')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
        ]);
        $category->update($data);

        return back()->with('success', 'Kategoriya yangilandi.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->post()->exists()) {
            return back()->withErrors(['category' => 'Bu kategoriyada postlar bor. Avval postlarni boshqa kategoriyaga o‘tkazing.']);
        }
        $category->delete();

        return back()->with('success', 'Kategoriya o‘chirildi.');
    }

    public function tags(Request $request)
    {
        $search = trim($request->string('search')->toString());

        $items = Tag::withCount('posts')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.Str::lower($search).'%']);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.taxonomy.index', [
            'pageTitle' => 'Teglar',
            'items' => $items,
            'indexRoute' => 'admin.tags.index',
            'storeRoute' => 'admin.tags.store',
            'updateRoute' => 'admin.tags.update',
            'deleteRoute' => 'admin.tags.destroy',
            'search' => $search,
        ]);
    }

    public function storeTag(Request $request)
    {
        $request->merge(['name' => Str::title(trim((string) $request->input('name')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);
        Tag::create($data);

        return back()->with('success', 'Teg yaratildi.');
    }

    public function updateTag(Request $request, Tag $tag)
    {
        $request->merge(['name' => Str::title(trim((string) $request->input('name')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)],
        ]);
        $tag->update($data);

        return back()->with('success', 'Teg yangilandi.');
    }

    public function destroyTag(Tag $tag)
    {
        $tag->posts()->detach();
        $tag->delete();

        return back()->with('success', 'Teg o‘chirildi.');
    }
}
