<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
        Category::create(['name' => 'Web Development']);
        Category::create(['name' => 'Web Design']);
        Category::create(['name' => 'Online market']);
        Category::create(['name' => 'Email market']);
    }
}
