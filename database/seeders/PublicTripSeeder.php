<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PublicTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('public_trips')->insert([
            [
                'user_id' => 1,
                'guide_id' => 1,
                'name' => 'جولة في المدينة القديمة',
                'city' => 'الرياض',
                'overview' => 'جولة شاملة في أبرز معالم المدينة القديمة مع مرشد سياحي متخصص.',
                'short_overview' => 'استكشف التاريخ العريق للمدينة القديمة',
                'images' => json_encode(['trip1.jpg', 'trip2.jpg']),
                'date_of_tour' => Carbon::now()->addDays(7),
                'meeting_point' => 'ساحة المدينة الرئيسية',
                'language' => 'العربية',
                'price_per_person' => 150.00,
                'available_seats' => 20,
                'status' => 'published',
                'visibility' => 'public',
                'is_removable' => true,
                'is_guide_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'guide_id' => 2,
                'name' => 'مغامرة الصحراء',
                'city' => 'جدة',
                'overview' => 'تجربة فريدة في صحراء جدة مع ركوب الجمال ومشاهدة الغروب.',
                'short_overview' => 'مغامرة صحراوية لا تنسى',
                'images' => json_encode(['desert1.jpg', 'desert2.jpg']),
                'date_of_tour' => Carbon::now()->addDays(14),
                'meeting_point' => 'فندق الصحراء الكبير',
                'language' => 'الإنجليزية',
                'price_per_person' => 250.00,
                'available_seats' => 15,
                'status' => 'published',
                'visibility' => 'public',
                'is_removable' => true,
                'is_guide_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'guide_id' => null,
                'name' => 'جولة تاريخية في القلعة',
                'city' => 'الدمام',
                'overview' => 'استكشف القلعة التاريخية ومعالمها الأثرية العريقة.',
                'short_overview' => 'رحلة إلى قلب التاريخ',
                'images' => json_encode(['castle1.jpg']),
                'date_of_tour' => Carbon::now()->addDays(21),
                'meeting_point' => 'بوابة القلعة الشرقية',
                'language' => 'العربية',
                'price_per_person' => 75.00,
                'available_seats' => 10,
                'status' => 'draft',
                'visibility' => 'private',
                'is_removable' => true,
                'is_guide_confirmed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
