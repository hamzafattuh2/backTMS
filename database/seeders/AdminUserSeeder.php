<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // تعطيل قيود الـ Foreign Key مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // حذف السجلات مع إعادة ترقيم الـ IDs
        DB::table('users')->truncate();

        // إعادة تفعيل قيود الـ Foreign Key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // إدخال المستخدمين
        DB::table('users')->insert([
            [
                'user_name' => 'admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@1234'),
                'type' => 'admin',
                'phone_number' => '+1234567890',
                'gender' => 'male',
                'profile_image' => null,
                'birth_date' => '1990-01-01',
                'code' => null,
                'expire_at' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'supervisor',
                'first_name' => 'Super',
                'last_name' => 'Visor',
                'email' => 'supervisor@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Super@1234'),
                'type' => 'admin',
                'phone_number' => '+1234567891',
                'gender' => 'female',
                'profile_image' => null,
                'birth_date' => '1992-05-15',
                'code' => null,
                'expire_at' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
