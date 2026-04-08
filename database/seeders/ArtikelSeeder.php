<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        Artikel::create([
            'user_id' => $user->id,
            'judul' => 'Keindahan Wisata Merauke',
            'slug' => Str::slug('Keindahan Wisata Merauke'),
            'isi' => 'Merauke memiliki banyak tempat wisata menarik yang wajib dikunjungi...',
            'gambar' => null,
            'image_public_id' => '1000shdt',
            'is_active' => true,
            'is_verified' => true
        ]);

        Artikel::create([
            'user_id' => $user->id,
            'judul' => 'Makanan Khas Papua yang Wajib Dicoba',
            'slug' => Str::slug('Makanan Khas Papua yang Wajib Dicoba'),
            'isi' => 'Papua memiliki berbagai makanan khas yang unik dan lezat...',
            'gambar' => null,
            'image_public_id' => '1000shdt',
            'is_active' => true,
            'is_verified' => true
        ]);
    }
}
