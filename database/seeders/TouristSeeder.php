<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tourist;

class TouristSeeder extends Seeder
{
    public function run()
    {
        // إنشاء مستخدمين سياحيين
        $user1 = User::create([
            'user_name' => 'tourist1',
            'first_name' => 'Ahmed',
            'last_name' => 'Mohamed',
            'email' => 'tourist1@example.com',
            'password' => bcrypt('password123'),
            'type' => 'tourist',
            'phone_number' => '+201234567890',
            'gender' => 'male',
            'birth_date' => '1990-05-15',
        ]);

        $user2 = User::create([
            'user_name' => 'tourist2',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'tourist2@example.com',
            'password' => bcrypt('password123'),
            'type' => 'tourist',
            'phone_number' => '+201098765432',
            'gender' => 'female',
            'birth_date' => '1985-11-22',
        ]);

        // إنشاء بيانات السياح المرتبطة بالمستخدمين
        Tourist::create([
            'user_id' => $user1->id,
            'nationality' => 'Egyptian',
            'emergency_contact' => '+201112223334',
        ]);

        Tourist::create([
            'user_id' => $user2->id,
            'nationality' => 'American',
            'emergency_contact' => '+201445556667',
        ]);

        // يمكنك إضافة المزيد من السياح هنا
        $user3 = User::create([
            'user_name' => 'tourist3',
            'first_name' => 'Yusuke',
            'last_name' => 'Tanaka',
            'email' => 'tourist3@example.com',
            'password' => bcrypt('password123'),
            'type' => 'tourist',
            'phone_number' => '+81234567890',
            'gender' => 'male',
            'birth_date' => '1988-07-30',
        ]);

        Tourist::create([
            'user_id' => $user3->id,
            'nationality' => 'Japanese',
            'emergency_contact' => '+81987654321',
        ]);
    }
}
