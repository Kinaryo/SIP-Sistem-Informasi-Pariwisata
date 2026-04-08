<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\User;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $foto = "https://res.cloudinary.com/ddrepuzxq/image/upload/v1775369728/produk/nbkq2lbsjfem8djxsas3.jpg";
        Produk::create([
            'user_id' => $user->id,
            'nama_produk' => 'Keripik Sagu Merauke',
            'deskripsi' => 'Keripik khas Merauke yang gurih dan renyah',
            'harga' => 25000,
            'foto' => $foto,
            'image_public_id' => '1000shdt',
            'is_active' => true,
            'is_verified' => true
        ]);

        Produk::create([
            'user_id' => $user->id,
            'nama_produk' => 'Ikan Asap',
            'deskripsi' => 'Ikan asap khas Papua dengan cita rasa unik',
            'harga' => 50000,
            'foto' => $foto,
            'image_public_id' => '1000shdt',
            'is_active' => true,
            'is_verified' => true
        ]);
    }
}
