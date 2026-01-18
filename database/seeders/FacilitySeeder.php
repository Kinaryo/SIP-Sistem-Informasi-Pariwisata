<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use Illuminate\Support\Str;
use Nette\Utils\Image;

class FacilitySeeder extends Seeder
{
    public function run()
    {
        $facilities = ['Parkir', 'Toilet', 'Mushola', 'Restoran'];

        foreach ($facilities as $facility) {
            Facility::create([
                'name' => $facility,
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Kawah_Putih_2.jpg',
            ]);
        }
    }
}
