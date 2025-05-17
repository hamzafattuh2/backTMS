<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    public function run()
    {
        Hotel::create([
            'name' => 'Sunset Paradise Hotel',
            'location' => 'Cairo, Egypt',
            'description' => 'A luxurious hotel with stunning views of the Nile.',
            'price_per_night' => 150.00,
            'stars' => 4,
            'amenities' => 'Wi-Fi,Pool,Gym,Spa',
            'contact_email' => 'info@sunsetparadise.com',
            'contact_phone' => '+202123456789',
            'is_active' => true,
        ]);

        Hotel::create([
            'name' => 'Desert Dream Resort',
            'location' => 'Luxor, Egypt',
            'description' => 'A peaceful resort near the ancient temples of Luxor.',
            'price_per_night' => 100.00,
            'stars' => 3,
            'amenities' => 'Wi-Fi,Restaurant,Parking',
            'contact_email' => 'contact@desertdream.com',
            'contact_phone' => '+20981234567',
            'is_active' => true,
        ]);
    }
}
