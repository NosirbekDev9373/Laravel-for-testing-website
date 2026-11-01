<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\CommentCreatedNotification;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::find($request->post_id);

        $comment = Comment::create([
            'body' => $request->body,
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
        ]);

        //O'zi comment qoldirsa xabar bormasligi
        if ($post->user_id !== auth()->id()) {
            $post->user->notifyNow(
                new CommentCreatedNotification(
                    postTitle:     $post->title,
                    postId:        $post->id,
                    commenterName: auth()->user()->name,
                    commentBody:   $request->body,
                )
            );
        }

        return redirect()->back();
    }
}
