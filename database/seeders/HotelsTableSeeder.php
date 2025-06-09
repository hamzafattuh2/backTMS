<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class HotelsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            DB::table('hotels')->insert([
                'name' => $faker->company . ' Hotel',
                'city' => $faker->city,
                'address' => $faker->address,
                'rating' => $faker->randomFloat(1, 1, 5),
                'number_of_reviews' => $faker->numberBetween(0, 500),
                'price_per_night' => $faker->randomFloat(2, 50, 500),
                'images' => json_encode([

                            'main_image' => 'images/1.png',
                    'sub_image1' => 'images/2.png',
                    'sub_image2' => 'images/3.png',
                    'sub_image3' => 'images/4.png',
                    'sub_image4' => 'images/5.png',
                ]),
                'description' => $faker->paragraph(4),
                'stars' => $faker->numberBetween(1, 5),
                'amenities' => json_encode($faker->randomElements([
                    'Free Wi-Fi', 'Parking', 'Pool', 'Gym', 'Spa', 'Breakfast Included'
                ], rand(2, 5))),
                'contact_email' => $faker->unique()->safeEmail,
                'contact_phone' => $faker->phoneNumber,
                'is_active' => $faker->boolean(90), // 90% chance to be active
                'guide_name' => $faker->name,
                'available_seats' => $faker->numberBetween(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
