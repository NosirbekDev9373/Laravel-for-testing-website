<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'gallery_images' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'short_content',
        'content',
        'image',
        'price',
        'image_hover',
        'duration',
        'gallery_images',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->image ?: $this->image_hover;

                return $path
                    ? asset('storage/'.$path)
                    : asset('images/post-placeholder.svg');
            },
        );
    }

    protected function hoverImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->image_hover ?: $this->image;

                return $path
                    ? asset('storage/'.$path)
                    : asset('images/post-placeholder.svg');
            },
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
