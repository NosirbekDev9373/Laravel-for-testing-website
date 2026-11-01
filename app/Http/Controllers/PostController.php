<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PostDeletedNotification;
use Illuminate\Support\Facades\Mail;
use App\Events\PostCreated as PostCreatedEvent;
use App\Http\Requests\StorePostRequest;
use App\Jobs\ChangePost;
use App\Jobs\UploadBigFile;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Policies\PostPolicy;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\PostCreatedNotification;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    use AuthorizesRequests;

    //Index
    public function index(Request $request)
    {
        $categories = Category::all();

        $cacheKey = 'posts_category_' . $request->get('category', 'all');

        $postIds = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($request) {
            return Post::when($request->category, function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
                ->latest()
                ->pluck('id')
                ->toArray();
        });
        // IDlar bo'yicha to'liq model oliw
        $posts = Post::whereIn('id', $postIds)
            ->latest()
            ->paginate(4);

        return view('posts.index', compact('posts', 'categories'));
        //return Post::limit(10)->get();
    }

    //Create
    public function create()
    {
        return view('posts.create')->with([
            'categories' => \App\Models\Category::all(),
            'tags' => \App\Models\Tag::all()
        ]);
    }

    //Stor
    public function store(StorePostRequest $request)
    {
        //dd($request);

        // asosiy rasmimiz
        $imagePath = null;
        if ($request->hasFile('image')) {
            $name = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('post-images', $name, 'public');
        }
        //Hover rasmimz
        $imageHoverPath = null;
        if ($request->hasFile('image_hover')) {
            $name = $request->file('image_hover')->getClientOriginalName();
            $imageHoverPath = $request->file('image_hover')->storeAs('post-images', $name, 'public');
        }
        //Galareya rasmlari
        $galleryPath = null;
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $galleryPath[] = $file->storeAs('post-images', $name, 'public');
            }
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
            'gallery_images' => $galleryPath,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        PostCreatedEvent::dispatch($post);

        //ChangePost::dispatch($post)->onQueue('uploading');

        /*Mail::to(auth()->user()->email)
            ->queue((new PostCreatedMail($post))->onQueue('sending-mails'));*/

        // Notification — queue siz
        auth()->user()->notifyNow(
            new PostCreatedNotification($post->title, $post->id)
        );
        Cache::flush();
        return redirect()->route('posts.index')->with('success', __('Post muvaffaqiyatli yaratildi!'));
    }

    //show
    public function show(Post $post)
    {
        return view('posts.show')->with([
            'post' => $post,
            'related_services' => Post::latest()->get()->except($post->id)->take(5),
            ]);
    }

    //Edit
    public function edit(Post $post)
    {
        /*Gate::authorize('update-post', $post);*/
        $this->authorize('update', $post);


        $categories = \App\Models\Category::all();
        return view('posts.edit')->with([
            'post' => $post,
            'categories' => $categories,
            ]);
    }

    //Update
    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = [
            'title'         => $request->input('title'),
            'category_id'   => $request->input('category_id'),
            'short_content' => $request->input('short_content'),
            'content'       => $request->input('content'),
            'price'         => $request->input('price'),
            'duration'      => $request->input('duration'),
        ];

        // Asosiy rasm
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $name = $request->file('image')->getClientOriginalName();
            $data['image'] = $request->file('image')->storeAs('post-images', $name, 'public');
        }

        // Hover rasm
        if ($request->hasFile('image_hover')) {
            if ($post->image_hover) {
                Storage::disk('public')->delete($post->image_hover);
            }
            $name = $request->file('image_hover')->getClientOriginalName();
            $data['image_hover'] = $request->file('image_hover')->storeAs('post-images', $name, 'public');
        }

        // Galereya
        if ($request->hasFile('gallery_images')) {
            if ($post->gallery_images) {
                foreach ($post->gallery_images as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $galleryPath = [];
            foreach ($request->file('gallery_images') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $galleryPath[] = $file->storeAs('post-images', $name, 'public');
            }
            $data['gallery_images'] = $galleryPath;
        }

        $post->update($data);
        Cache::flush();
        return redirect()->route('posts.index')->with('success', __('Post muvaffaqiyatli tahrirlandi!'));
    }

    //Delete
    public function destroy(Post $post)
    {
        /*Gate::authorize('delete-post', $post);*/
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
        Cache::flush();
        $user->notifyNow(new PostDeletedNotification($post));

        return redirect()->route('posts.index')->with('danger', __('Post muvaffaqiyatli o\'chirildi!'));
    }
}
