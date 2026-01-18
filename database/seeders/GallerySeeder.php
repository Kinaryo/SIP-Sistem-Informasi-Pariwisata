<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $galleries = [
            1 => [
                'https://upload.wikimedia.org/wikipedia/commons/1/1e/Kawah_Putih_2.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/5/5f/Kawah_Putih_3.jpg',
            ],
            2 => [
                'https://upload.wikimedia.org/wikipedia/commons/2/2e/Bromo_Tengger_Semeru.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/4/4d/Bromo_Sunrise.jpg',
            ],
            3 => [
                'https://upload.wikimedia.org/wikipedia/commons/6/6d/Kuta_Lombok_Beach.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/7/7e/Pantai_Kuta_Lombok.jpg',
            ],
            4 => [
                'https://upload.wikimedia.org/wikipedia/commons/4/4b/Prambanan_Indonesia.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/1/19/Prambanan_Temple.jpg',
            ],
            5 => [
                'https://upload.wikimedia.org/wikipedia/commons/a/a5/Taman_Ayun_Temple.jpg',
                'https://upload.wikimedia.org/wikipedia/commons/9/97/Taman_Ayun_Bali.jpg',
            ],
        ];

        // Inisialisasi counter untuk title
        $counter = 1;

        foreach ($galleries as $placeId => $images) {
            foreach ($images as $image) {
                Gallery::create([
                    'tourism_place_id' => $placeId,
                    'image' => $image,
                    'title' => 'Gambar ke-' . $counter // Menggunakan titik (.) untuk gabung string
                ]);
                
                $counter++; // Tambahkan angka setelah insert
            }
        }
    }
}