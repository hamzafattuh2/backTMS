<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = [
            [
                'name' => 'Al Mashrabya',
                'location' => 'Cairo, Egypt',
                'description' => 'Traditional Egyptian cuisine with a modern twist.',
                'cuisine' => 'Egyptian',
                'price_range' => '$$',
                'contact_email' => 'info@mashrabya.com',
                'contact_phone' => '+20 100 111 2222',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Spice Route',
                'location' => 'Alexandria, Egypt',
                'description' => 'A journey through flavors of Asia.',
                'cuisine' => 'Asian',
                'price_range' => '$$$',
                'contact_email' => 'contact@spiceroute.com',
                'contact_phone' => '+20 122 333 4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bella Italia',
                'location' => 'Giza, Egypt',
                'description' => 'Authentic Italian pizzas and pastas.',
                'cuisine' => 'Italian',
                'price_range' => '$$',
                'contact_email' => 'hello@bellaitalia.com',
                'contact_phone' => '+20 155 666 7777',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Restaurant::insert($restaurants);
    }
}
