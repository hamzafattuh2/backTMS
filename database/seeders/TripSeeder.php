<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\TripActivity;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TripSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_US');

        // تعطيل فحص المفاتيح الأجنبية مؤقتاً
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        TripActivity::query()->delete();
        Trip::query()->delete();

        // تمكين فحص المفاتيح الأجنبية مرة أخرى
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // إنشاء 20 رحلة وهمية باللغة الإنجليزية
        for ($i = 1; $i <= 20; $i++) {
         $startDate = Carbon::now()->addDays(rand(1, 60));
$endDate = (clone $startDate)->addDays(rand(1, 14)); // هذا يضمن أن endDate بعد startDate
            $trip = Trip::create([
                'user_id' => 1,
                'guide_id' => 2,
                'name' => $faker->words(3, true) . ' Tour', // مثال: "Beautiful Mountain Tour"
                'city' => $faker->city,
                'overview' => $faker->paragraph(3),
                'short_overview' => $faker->sentence(10),
                'main_image' => 'trip_images/main_' . $i . '.jpg',
                'gallery_images' => json_encode([
                    'trip_images/gallery1_' . $i . '.jpg',
                    'trip_images/gallery2_' . $i . '.jpg',
                    'trip_images/gallery3_' . $i . '.jpg',
                    'trip_images/gallery4_' . $i . '.jpg'
                ]),
                'start_at' => $startDate,
                'end_at' => $endDate,
                'language' => $faker->randomElement(['English', 'Arabic', 'French', 'Spanish']),
                'duration_days' => abs($endDate->diffInDays($startDate)),
                // 'duration_days' => $endDate->diffInDays($startDate),
                'price_per_night' => $faker->randomFloat(2, 100, 500),
                'available_seats' => $faker->numberBetween(5, 30),
                'status' => $faker->randomElement(['draft', 'published', 'ongoing', 'completed', 'cancelled']),
                'visibility' => $faker->randomElement(['public', 'private']),
                'is_removable' => $faker->boolean(90),
                'is_guide_confirmed' => $faker->boolean(70),
            ]);

            // إنشاء أنشطة لكل رحلة
            $this->createActivitiesForTrip($trip, $faker);
        }
    }

    protected function createActivitiesForTrip($trip, $faker)
    {
        $daysCount = $trip->duration_days;
        $currentDate = clone $trip->start_at;

        for ($day = 1; $day <= $daysCount; $day++) {
            $activityCount = rand(1, 4);

            for ($a = 1; $a <= $activityCount; $a++) {
                TripActivity::create([
                    'trip_id' => $trip->id,
                    'title' => $faker->words(2, true) . ' Activity', // مثال: "City Tour Activity"
                    'description' => $faker->paragraph(2),
                    'day_number' => 'day' . $day,
                    'date' => $currentDate->format('Y-m-d'),
                ]);
            }

            $currentDate->addDay();
        }
    }
}
