<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Post::limit(10)->get();
        return PostResource::collection(Post::limit(10)->get());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            'user_id' => 1,
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

        return response(['success' => 'Post muvaffaqiyatli yaratildi!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response(['success' => 'Post muvaffaqiyatli o\'chirildi!']);
    }
}
