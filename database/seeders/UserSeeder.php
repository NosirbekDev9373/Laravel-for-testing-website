<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make(env('SEED_DEFAULT_PASSWORD', 'Demo@12345'));

        $users = [
            ['name' => 'Administrator', 'email' => 'admin@tozalash.uz', 'role' => 'admin'],
            ['name' => 'Sardor Rahimov', 'email' => 'sardor@tozalash.uz', 'role' => 'editor'],
            ['name' => 'Dilnoza Karimova', 'email' => 'dilnoza@tozalash.uz', 'role' => 'blogger'],
            ['name' => 'Javohir Tursunov', 'email' => 'javohir@tozalash.uz', 'role' => 'blogger'],
            ['name' => 'Aziza Yusupova', 'email' => 'aziza@tozalash.uz', 'role' => 'user'],
            ['name' => 'Malika Abdullayeva', 'email' => 'malika@tozalash.uz', 'role' => 'user'],
            ['name' => 'Bekzod Ismoilov', 'email' => 'bekzod@tozalash.uz', 'role' => 'user'],
            ['name' => 'Nigora Saidova', 'email' => 'nigora@tozalash.uz', 'role' => 'user'],
            ['name' => 'Rustam Aliyev', 'email' => 'rustam@tozalash.uz', 'role' => 'user'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => $password],
            );

            $role = Role::firstOrCreate(['name' => $data['role']]);
            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
