<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Wisata',
            'email' => 'jelajahiadmin@gmail.com',
            'password' => Hash::make('Kinaryo733@'),
            'role' => 'admin',
            'address' => 'Pariwisata'
        ]);

        User::create([
            'name' => 'Kinaryo',
            'email' => 'kinaryo733huda@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'address' => 'Merauke'
        ]);

        User::create([
            'name' => 'Zahra',
            'email' => 'zahrah@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'address' => 'Merauke'
        ]);
    }
}
