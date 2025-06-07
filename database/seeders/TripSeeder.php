<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\User;
use App\Models\TourGuide;
use App\Models\TripPriceSuggestion;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود مستخدمين لاستخدامهم كـ user_id و guide_id
        $users = User::take(1)->get();
    $guide = TourGuide::take(2)->get();
        // if ($users->count() < 3) {
        //     $this->command->info('Please seed at least 3 users before running TripSeeder.');
        //     return;
        // }

        $user1 = $users[0]; // السائح
        $guide1 = $guide[0]; // مرشد صحيح
        $guide2 = $guide[1]; // مرشد خاطئ لحالة الاختبار

        // إضافة الرحلات الأصلية
        Trip::insert([
            [
                'user_id' => $users[0]->id,
                'guide_id' => $guide1->id,
                'title' => 'Explore the Nile',
                'description' => 'A 5-day journey exploring the beauty of the Nile River.',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'language_guide' => 'English',
                'days_count' => 5,
                'price' => 1200.00,
                'status' => 'upcoming',
                'public_or_private' => 'public',
                'delete_able' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => null,
                'title' => 'Desert Safari Adventure',
                'description' => 'tripsWithoutGuide',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(17),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'pending',
                'public_or_private' => 'private',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => null,
                'title' => 'Desert Safari Adventure',
                'description' => 'tripsWithoutGuide',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(17),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'waiting_guide',
                'public_or_private' => 'private',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => $guide1->id,
                'title' => 'ongoing trip public',
                'description' => 'Experience the thrill of the desert in this private tour.',
                'start_date' => now()->addDays(11),
                'end_date' => now()->addDays(14),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'ongoing',
                'public_or_private' => 'public',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => $guide2->id,
                'title' => 'ongoing trip private',
                'description' => 'Experience the thrill of the desert in this private tour.',
                'start_date' => now()->addDays(11),
                'end_date' => now()->addDays(14),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'ongoing',
                'public_or_private' => 'private',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => $guide1->id,
                'title' => 'guideCompletedPrivateTrips',
                'description' => 'Experience the thrill of the desert in this private tour.',
                'start_date' => now()->addDays(11),
                'end_date' => now()->addDays(14),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'ongoing',
                'public_or_private' => 'private',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users[0]->id,
                'guide_id' => $guide2->id,
                'title' => 'guideCompletedPublicTrips',
                'description' => 'Experience the thrill of the desert in this private tour.',
                'start_date' => now()->addDays(11),
                'end_date' => now()->addDays(14),
                'language_guide' => 'Arabic',
                'days_count' => 2,
                'price' => 800.00,
                'status' => 'ongoing',
                'public_or_private' => 'public',
                'delete_able' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // إضافة الحالات الجديدة
        $trip4 = Trip::create([
            'user_id' => $user1->id,
            'guide_id' => null,
            'title' => 'Trip Case 1 ',
            'description' => 'Case 1: Trip is public',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(2),
            'language_guide' => 'English',
            'days_count' => 1,
            'price' => null,
            'status' => 'pending',
            'public_or_private' => 'public',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trip5 = Trip::create([
            'user_id' => $user1->id,
            'guide_id' => $guide2->id,
            'title' => 'Trip Case 2',
            'description' => 'Case 2: Guide already assigned and not current user',
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(5),
            'language_guide' => 'French',
            'days_count' => 2,
            'price' => null,
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trip6 = Trip::create([
            'user_id' => $user1->id,
            'guide_id' => null,
            'title' => 'Trip Case 3',
            'description' => 'Case 3: Price already set in trip',
            'start_date' => now()->addDays(4),
            'end_date' => now()->addDays(6),
            'language_guide' => 'German',
            'days_count' => 2,
            'price' => 900.00,
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trip7 = Trip::create([
            'user_id' => $user1->id,
            'guide_id' => null,
            'title' => 'Trip Case 4',
            'description' => 'Case 4: Suggestion already accepted',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(10),
            'language_guide' => 'Spanish',
            'days_count' => 3,
            'price' => null,
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        TripPriceSuggestion::create([
            'trip_id' => $trip7->id,
            'guide_id' => $guide1->id,
            'price' => 1000,
            'is_accepted' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $trip8 = Trip::create([
            'user_id' => $user1->id,
            'guide_id' => null,
            'title' => 'Trip Case 5',
            'description' => 'Case 5: Pending suggestion already exists',
            'start_date' => now()->addDays(8),
            'end_date' => now()->addDays(9),
            'language_guide' => 'Arabic',
            'days_count' => 1,
            'price' => null,
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        TripPriceSuggestion::create([
            'trip_id' => $trip8->id,
            'guide_id' => $guide1->id,
            'price' => 800,
            'is_accepted' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Trip::create([
            'user_id' => $user1->id,
            'guide_id' => null,
            'title' => 'Trip Case 6',
            'description' => 'Case 6: Success - Eligible to offer price',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(12),
            'language_guide' => 'Italian',
            'days_count' => 2,
            'price' => null,
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
