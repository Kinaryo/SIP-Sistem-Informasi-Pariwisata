<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteAccessCount;

class SiteAccessCountSeeder extends Seeder
{
    public function run(): void
    {
      
        SiteAccessCount::updateOrCreate(
            ['id' => 0], 
            ['total_access' => 50] 
        );
    }
}
