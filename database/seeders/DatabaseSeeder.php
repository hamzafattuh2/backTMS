<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(RestaurantSeeder::class);

        $this->call(TouristSeeder::class);
        $this->call(TourGuideSeeder::class);
        $this->call(TripSeeder::class);
        $this->call(TripPriceSuggestionSeeder::class);
        $this->call(TouristSitesSeeder::class);

        // يمكنك إضافة باقي Seeders هنا

    }
}
