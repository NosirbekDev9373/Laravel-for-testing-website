<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'category_id' => rand(1, 4),
            'title' => fake()->sentence(),
            'short_content' => fake()->sentence(15),
            'content' => fake()->paragraph(5),
            'price' => fake()->randomFloat(2, 10, 500),
            'duration' => fake()->numberBetween(1, 10),
            'image' => null,
            'image_hover' => null,
            'gallery_images' => null,
        ];
    }
}
