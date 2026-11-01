<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class DeleteOldPost extends Command
{
    protected $signature = 'posts:delete-one';
    protected $description = 'Har daqiqada eng eski 1 postni o\'chiradi';

    public function handle()
    {
        // Eng eski postni olish uchun oldest() ishlatamiz (latest() eng yangisini oladi)
        $post = Post::oldest()->first();

        if (!$post) {
            $this->info('O\'chiriladigan post yoq');
            return;
        }

        // Rasmlarni o'chirish
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        if ($post->image_hover) {
            Storage::disk('public')->delete($post->image_hover);
        }
        if ($post->gallery_images) {
            foreach ($post->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $title = $post->title;
        $post->delete();

        // Cache tozalash
        Cache::flush();

        $this->info("Post o'chirildi: {$title}");
    }
}
