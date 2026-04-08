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
            FacilitySeeder::class,
            SettingSeeder::class,
            SiteAccessCountSeeder::class,
            QuizSeeder::class,



            // LocationSeeder::class,
            // TourismPlaceSeeder::class,
            // GallerySeeder::class,
            // ReviewSeeder::class,
            // FavoriteSeeder::class,
            // VisitSeeder::class,
            // EventSeeder::class,
            // EventRegistrationSeeder::class,
            // ProdukSeeder::class,
            // ArtikelSeeder::class,
            // TokoSeeder::class,
        ]);
    }
}
