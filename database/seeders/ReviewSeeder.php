<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Review::create([
            'user_id' => 2,
            'tourism_place_id' => 1,
            'rating' => 5,
            'comment' => 'Tempatnya sangat indah!'
        ]);
    }
}
