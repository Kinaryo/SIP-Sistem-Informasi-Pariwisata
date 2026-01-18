<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourismPlace;
use App\Models\User;
use Illuminate\Support\Str;

class TourismPlaceSeeder extends Seeder
{
    public function run()
    {
        // ✅ Ambil admin / user pertama sebagai author
        $author = User::first();

        if (!$author) {
            $this->command->error('Seeder gagal: tidak ada user');
            return;
        }

        $defaultImage = 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Taman_0_Kilometer_Merauke_-_Sabang.jpg/960px-Taman_0_Kilometer_Merauke_-_Sabang.jpg';

        $places = [
            [
                'category_id' => 1,
                'location_id' => 1,
                'name' => 'Kawah Putih',
                'price' => 25000,
            ],
            [
                'category_id' => 1,
                'location_id' => 4,
                'name' => 'Gunung Bromo',
                'price' => 35000,
            ],
            [
                'category_id' => 1,
                'location_id' => 5,
                'name' => 'Pantai Kuta Lombok',
                'price' => 15000,
            ],
            [
                'category_id' => 2,
                'location_id' => 3,
                'name' => 'Candi Prambanan',
                'price' => 40000,
            ],
            [
                'category_id' => 2,
                'location_id' => 2,
                'name' => 'Pura Taman Ayun',
                'price' => 30000,
            ],
        ];

        foreach ($places as $place) {
            TourismPlace::create([
                'user_id' => $author->id,
                'category_id' => $place['category_id'],
                'location_id' => $place['location_id'],
                'name' => $place['name'],
                'slug' => Str::slug($place['name']), // ✅ WAJIB
                'description' => 'Deskripsi wisata ' . $place['name'],
                'ticket_price' => $place['price'],
                'open_time' => '08:00',
                'close_time' => '17:00',
                'contact' => '08123456789',
                'cover_image' => $defaultImage,
                'is_active' => true,
                'is_verified' => true
            ]);
        }
    }
}
