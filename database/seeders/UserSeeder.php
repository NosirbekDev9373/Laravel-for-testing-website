<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin123',
            'email' => 'admin123@example.com',
            'password' => Hash::make('123123123'),
        ]);

        $user->roles()->attach([1,3]);

        $user2 = User::create([
            'name' => 'Ali',
            'email' => 'Ali@gmail.com',
            'password' => Hash::make('123123123'),
        ]);

        $user2->roles()->attach([2]);
    }
}
