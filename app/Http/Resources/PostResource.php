<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            //'user' => new UserResource($this->user),
            'user' => $this->user->name,
            'title' => $this->title,
            'short_content' => $this->short_content,
            'content' => $this->content,
            'price' => $this->price,
            'duration' => $this->duration,
            'tags' => $this->tags,
            'category' => $this->category->name,
        ];
    }
}
