<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    public function saved(Post $post): void
    {
        $this->clearIndexCache();
    }

    public function deleted(Post $post): void
    {
        $this->clearIndexCache();
    }

    private function clearIndexCache(): void
    {
        Cache::forget('posts_category_all');

        foreach (Category::pluck('id') as $categoryId) {
            Cache::forget('posts_category_'.$categoryId);
        }
    }
}
