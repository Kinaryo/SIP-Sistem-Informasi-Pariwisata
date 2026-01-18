<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    public function run()
    {
        Favorite::create([
            'user_id' => 2,
            'tourism_place_id' => 1
        ]);
    }
}
