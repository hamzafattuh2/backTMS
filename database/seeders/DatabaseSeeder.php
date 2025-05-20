<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            // Add other seeders here
        ]);
        $this->call(HotelSeeder::class);
        $this->call(RestaurantSeeder::class);

        $this->call( TouristSeeder::class);
        $this->call( TourGuideSeeder::class);
          $this->call(TripSeeder::class);
        $this->call(TripPriceSuggestionSeeder::class);

            // يمكنك إضافة باقي Seeders هنا

    }
}
