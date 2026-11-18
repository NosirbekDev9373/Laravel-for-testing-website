<?php

namespace App\Http\Controllers;

use App\Events\PostCreated as PostCreatedEvent;
use App\Http\Requests\StorePostRequest;
use App\Jobs\ChangePost;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use App\Notifications\PostDeletedNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use AuthorizesRequests;

    // Index
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $categoryId = filter_var($request->query('category'), FILTER_VALIDATE_INT);
        $categoryId = $categoryId !== false && $categories->contains('id', $categoryId)
            ? $categoryId
            : null;

        $cacheKey = 'posts_category_'.($categoryId ?? 'all');

        $postIds = Cache::remember($cacheKey, now()->addMinute(), function () use ($categoryId) {
            return Post::when($categoryId !== null, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
                ->latest()
                ->pluck('id')
                ->toArray();
        });

        $posts = Post::whereIn('id', $postIds)
            ->latest()
            ->paginate(4)
            ->withQueryString();

        return view('posts.index', compact('posts', 'categories', 'categoryId'));
    }

    // Create
    public function create()
    {
        $this->authorize('create', Post::class);

        return view('posts.create')->with([
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    // Stor
    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);

        $imagePath = $request->file('image')->store('post-images', 'public');

        $imageHoverPath = $request->hasFile('image_hover')
            ? $request->file('image_hover')->store('post-images', 'public')
            : null;

        $galleryPath = [];
        foreach ($request->file('gallery_images', []) as $file) {
            $galleryPath[] = $file->store('post-images', 'public');
        }

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'short_content' => $request->input('short_content'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'duration' => $request->input('duration'),
            'image' => $imagePath,
            'image_hover' => $imageHoverPath,
            'gallery_images' => $galleryPath ?: null,
        ]);

        $post->tags()->sync($request->input('tags', []));

        PostCreatedEvent::dispatch($post);

        // ChangePost::dispatch($post)->onQueue('uploading');

        /*Mail::to(auth()->user()->email)
            ->queue((new PostCreatedMail($post))->onQueue('sending-mails'));*/

        // Notification — queue siz
        auth()->user()->notifyNow(
            new PostCreatedNotification($post->title, $post->id)
        );
        $this->clearPostIndexCache();

        return redirect()->route('posts.index')->with('success', __('Post muvaffaqiyatli yaratildi!'));
    }

    // show
    public function show(Post $post)
    {
        $post->load(['category', 'user', 'tags'])->loadCount('comments');

        $comments = $post->comments()
            ->with('user')
            ->latest('id')
            ->paginate(5);

        $relatedServices = Post::whereKeyNot($post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('posts.show')->with([
            'post' => $post,
            'comments' => $comments,
            'related_services' => $relatedServices,
        ]);
    }

    // Edit
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit')->with([
            'post' => $post->load('tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    // Update
    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = [
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'short_content' => $request->input('short_content'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'duration' => $request->input('duration'),
        ];

        $oldFiles = [];

        // Asosiy rasm
        if ($request->hasFile('image')) {
            $oldFiles[] = $post->image;
            $data['image'] = $request->file('image')->store('post-images', 'public');
        }

        // Hover rasm
        if ($request->hasFile('image_hover')) {
            $oldFiles[] = $post->image_hover;
            $data['image_hover'] = $request->file('image_hover')->store('post-images', 'public');
        }

        // Galereya
        if ($request->hasFile('gallery_images')) {
            $oldFiles = array_merge($oldFiles, $post->gallery_images ?? []);

            $galleryPath = [];
            foreach ($request->file('gallery_images') as $file) {
                $galleryPath[] = $file->store('post-images', 'public');
            }
            $data['gallery_images'] = $galleryPath ?: null;
        }

        $post->update($data);
        $post->tags()->sync($request->input('tags', []));

        foreach (array_filter($oldFiles) as $oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        $this->clearPostIndexCache();

        return redirect()->route('posts.index')->with('success', __('Post muvaffaqiyatli tahrirlandi!'));
    }

    // Delete
    public function destroy(Post $post)
    {
        /* Gate::authorize('delete-post', $post); */
        $this->authorize('delete', $post);

        $user = $post->user;

        if (isset($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
        if (isset($post->image_hover)) {
            Storage::disk('public')->delete($post->image_hover);
        }
        if (isset($post->gallery_images)) {
            foreach ($post->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $post->delete();
        $this->clearPostIndexCache();
        $user->notifyNow(new PostDeletedNotification($post));

        return redirect()->route('posts.index')->with('danger', __('Post muvaffaqiyatli o\'chirildi!'));
    }

    private function clearPostIndexCache(): void
    {
        Cache::forget('posts_category_all');

        foreach (Category::pluck('id') as $categoryId) {
            Cache::forget('posts_category_'.$categoryId);
        }
    }
}
