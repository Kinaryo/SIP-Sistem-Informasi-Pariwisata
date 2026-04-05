<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Str;

class TokoSeeder extends Seeder
{
    public function run()
    {
        // Ambil user pertama dengan role 'user' (bukan admin)
        $user = User::where('role', 'user')->first();

        if($user) {
            Toko::create([
                'user_id' => $user->id,
                'nama_toko' => 'Toko Elektronik Maju',
                'slug' => Str::slug('Toko Elektronik Maju'),
                'deskripsi' => 'Toko elektronik terpercaya dengan berbagai produk terbaru.',
                'logo' => null,
                'telepon' => '081234567890',
                'telepon_aktif' => true, // tampilkan di halaman toko
                'image_public_id' => 'xxxxx'
            ]);
        }
    }
}