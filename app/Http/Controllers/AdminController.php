<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'postsCount' => Post::count(),
            'categoriesCount' => Category::count(),
            'tagsCount' => Tag::count(),
            'commentsCount' => Comment::count(),
            'latestPosts' => Post::with(['user', 'category'])->latest()->take(8)->get(),
            'latestUsers' => User::latest()->take(8)->get(),
        ]);
    }

    public function posts()
    {
        $posts = Post::with(['user', 'category'])->latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function createPost()
    {
        return view('admin.posts.form', [
            'post' => new Post(),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'editing' => false,
        ]);
    }

    public function storePost(Request $request)
    {
        $data = $this->validatePost($request, true);

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

        return redirect()->route('admin.posts.index')->with('success', 'Post yaratildi.');
    }

    public function editPost(Post $post)
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'editing' => true,
        ]);
    }

    public function updatePost(Request $request, Post $post)
    {
        $data = $this->validatePost($request, false);

        foreach (['image' => 'image', 'image_hover' => 'image_hover'] as $input => $column) {
            if ($request->hasFile($input)) {
                if ($post->{$column}) {
                    Storage::disk('public')->delete($post->{$column});
                }
                $data[$column] = $request->file($input)->store('post-images', 'public');
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach (($post->gallery_images ?? []) as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $gallery = [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $file->store('post-images', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.posts.index')->with('success', 'Post yangilandi.');
    }

    public function destroyPost(Post $post)
    {
        foreach ([$post->image, $post->image_hover] as $image) {
            if ($image) {
                Storage::disk('public')->delete($image);
            }
        }

        foreach (($post->gallery_images ?? []) as $image) {
            Storage::disk('public')->delete($image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post o‘chirildi.');
    }

    private function validatePost(Request $request, bool $creating): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'short_content' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'image' => [$creating ? 'nullable' : 'sometimes', 'image', 'max:5120'],
            'image_hover' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);
    }

    public function users(Request $request)
    {
        $users = User::with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function updateUserRole(Request $request, User $user)
    {
        if ($user->is($request->user()) && !$request->input('roles')) {
            return back()->withErrors(['roles' => 'O‘zingizning admin rolingizni olib tashlay olmaysiz.']);
        }

        $validated = $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'distinct', 'exists:roles,id'],
        ]);

        if ($user->is($request->user())) {
            $adminRoleId = Role::where('name', 'admin')->value('id');
            if (!$adminRoleId || !in_array($adminRoleId, $validated['roles'])) {
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

    public function comments()
    {
        $comments = Comment::with(['user', 'post'])->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Izoh o‘chirildi.');
    }

    public function categories()
    {
        return view('admin.taxonomy.index', [
            'pageTitle' => 'Kategoriyalar',
            'items' => Category::withCount('post')->orderBy('name')->get(),
            'storeRoute' => 'admin.categories.store',
            'deleteRoute' => 'admin.categories.destroy',
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);
        Category::create($data);
        return back()->with('success', 'Kategoriya yaratildi.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->post()->exists()) {
            return back()->withErrors(['category' => 'Bu kategoriyada postlar bor. Avval postlarni boshqa kategoriyaga o‘tkazing.']);
        }
        $category->delete();
        return back()->with('success', 'Kategoriya o‘chirildi.');
    }

    public function tags()
    {
        return view('admin.taxonomy.index', [
            'pageTitle' => 'Teglar',
            'items' => Tag::withCount('posts')->orderBy('name')->get(),
            'storeRoute' => 'admin.tags.store',
            'deleteRoute' => 'admin.tags.destroy',
        ]);
    }

    public function storeTag(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);
        Tag::create($data);
        return back()->with('success', 'Teg yaratildi.');
    }

    public function destroyTag(Tag $tag)
    {
        $tag->posts()->detach();
        $tag->delete();
        return back()->with('success', 'Teg o‘chirildi.');
    }
}
