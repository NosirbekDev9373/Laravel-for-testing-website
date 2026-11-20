<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('editor') || $user->hasRole('blogger');
    }

    public function view(User $user, Post $post): bool
    {
        return $user->hasRole('editor')
            || ($user->hasRole('blogger') && $user->id === $post->user_id);
    }

    /**
     * Post yaratish huquqi
     */
    public function create(User $user): bool
    {
        // Editor va Blogger post yarata oladi (Admin 'before' orqali o'tadi)
        return $user->hasRole('editor') || $user->hasRole('blogger');
    }

    /**
     * Postni tahrirlash (Update) huquqi
     */
    public function update(User $user, Post $post): bool
    {
        // 1. Editor istalgan postni o'zgartira oladi
        if ($user->hasRole('editor')) {
            return true;
        }

        // 2. Blogger faqat o'zining postini o'zgartira oladi
        if ($user->hasRole('blogger')) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->hasRole('editor')) {
            return true;
        }

        if ($user->hasRole('blogger')) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
