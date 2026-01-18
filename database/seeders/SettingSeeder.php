<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'office_name' => 'Kantor Pusat',
            'longitude' => 140.3915625,
            'latitude' => -8.5065904,
        ]);
    }
}

