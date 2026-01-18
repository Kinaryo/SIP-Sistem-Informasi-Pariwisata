<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;

class VisitSeeder extends Seeder
{
    public function run()
    {
        Visit::create([
            'tourism_place_id' => 1,
            'visitor_count' => 120,
            'visit_date' => now()
        ]);
    }
}
