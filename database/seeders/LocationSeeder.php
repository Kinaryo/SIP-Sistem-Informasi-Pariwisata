<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['Bandung', 'Jawa Barat'],
            ['Ubud', 'Bali'],
            ['Yogyakarta', 'DI Yogyakarta'],
            ['Malang', 'Jawa Timur'],
            ['Lombok', 'Nusa Tenggara Barat'],
        ];

        foreach ($locations as $loc) {
            Location::create([
                'province' => $loc[1],
                'city' => $loc[0],
                'district' => null,
                'address' => 'Alamat ' . $loc[0],
                'latitude' => -7.0,
                'longitude' => 110.0
            ]);
        }
    }
}
