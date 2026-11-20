<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Uy',
            'Ofis',
            'Gilam',
            'Deraza',
            'Dezinfeksiya',
            'Remont',
            'Avto',
            'HoReCa',
            'Brending',
            'Ekologik',
            'Tez xizmat',
            'Kafolat',
            'Professional',
            'Byudjet',
            'Ta’mirlash',
            'IT',
            'Go‘zallik',
            'Ta’lim',
            'Tadbirlar',
            'Sifat',
            'Ijodiy',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
