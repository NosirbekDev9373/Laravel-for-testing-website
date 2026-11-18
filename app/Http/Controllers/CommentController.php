<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentCreatedNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ]);

        $post = Post::findOrFail($validated['post_id']);

        $comment = Comment::create([
            'body' => $validated['body'],
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        // O'zi comment qoldirsa xabar bormasligi
        if ($post->user_id !== auth()->id()) {
            $post->user->notifyNow(
                new CommentCreatedNotification(
                    postTitle: $post->title,
                    postId: $post->id,
                    commenterName: auth()->user()->name,
                    commentBody: $validated['body'],
                )
            );
        }

        return redirect()->back()->with('success', __('Izoh qo‘shildi!'));
    }
}
