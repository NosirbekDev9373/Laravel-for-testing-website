<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Uy tozalash',
            'Ofis tozalash',
            'Gilam va mebel yuvish',
            'Deraza va fasad yuvish',
            'Dezinfeksiya',
            'Remontdan keyingi tozalash',
            'Avtomobil xizmatlari',
            'Kafe va restoran xizmatlari',
            'Brending va dizayn',
            'Ta’mirlash va remont',
            'IT va raqamli xizmatlar',
            'Go‘zallik va sog‘lomlashtirish',
            'Ta’lim va konsalting',
            'Tadbirlar va to‘yxonalar',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
