<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TourGuide;

class TourGuideSeeder extends Seeder
{
    public function run(): void
    {
        // عدّد كما تشاء
        $count = 5;

        for ($i = 1; $i <= $count; $i++) {

            /*---------------------------------------------------------
            | 1) إنشاء مستخدم (type = guide)
            *--------------------------------------------------------*/
            $user = User::create([
                'user_name'     => fake()->unique()->userName,
                'first_name'    => fake()->firstName,
                'last_name'     => fake()->lastName,
                'email'         => fake()->unique()->safeEmail,
                'password'      => Hash::make('password'),   // كلمة مرور افتراضيّة
                'type'          => 'guide',
                'phone_number'  => fake()->phoneNumber,
                'gender'        => fake()->randomElement(['male', 'female']),
                'profile_image' => null,
                'birth_date'    => fake()->date('Y-m-d', '-20 years'),
            ]);

            /*---------------------------------------------------------
            | 2) إنشاء سجل TourGuide مرتبط بالمستخدم
            *--------------------------------------------------------*/
            TourGuide::create([
                'user_id'             => $user->id,
                'languages'           => fake()->randomElement([
                                            'English,Arabic',
                                            'Spanish',
                                            'French,German'
                                        ]),
                'years_of_experience' => fake()->numberBetween(1, 20),
                'license_picture_path'=> 'licenses/'.fake()->uuid().'.png',
                'cv_path'             => 'cvs/'.fake()->uuid().'.pdf',
                'guide_picture_path'  => 'guides/'.fake()->uuid().'.jpg',
                'confirmByAdmin'      => fake()->boolean(80), // 80 % confirmed
            ]);
        }
    }
}
