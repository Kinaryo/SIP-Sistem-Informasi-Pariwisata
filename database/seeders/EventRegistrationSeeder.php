<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRegistration;

class EventRegistrationSeeder extends Seeder
{
    public function run()
    {
        EventRegistration::create([
            'event_id' => 1,
            'user_id' => 2
        ]);
    }
}
