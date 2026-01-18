<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            FacilitySeeder::class,
            TourismPlaceSeeder::class,
            GallerySeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
            VisitSeeder::class,
            EventSeeder::class,
            EventRegistrationSeeder::class,
            QuizSeeder::class,
            SettingSeeder::class,
            SiteAccessCountSeeder::class,
        ]);
    }
}
