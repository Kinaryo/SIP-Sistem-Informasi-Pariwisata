<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run()
    {
        Event::create([
            'tourism_place_id' => 1,
            'title' => 'Festival Alam Bandung',
            'slug' => Str::slug('Festival Alam Bandung'),
            'description' => 'Event tahunan wisata alam',
            'event_date' => now()->addDays(10),
            'start_time' => '09:00',
            'end_time' => '15:00'
        ]);
    }
}
