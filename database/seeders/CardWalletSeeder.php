<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
class CardWalletSeeder extends Seeder
{
 public function run()
{
    // تعطيل فحص المفاتيح الخارجية مؤقتاً
    Schema::disableForeignKeyConstraints();

    // حذف البيانات إذا كانت موجودة
    Wallet::query()->delete();
    Card::query()->delete();

    // إعادة تفعيل فحص المفاتيح
    Schema::enableForeignKeyConstraints();

    // إنشاء 10 مستخدمين مع محافظهم وبطاقاتهم
    for ($i = 1; $i <= 10; $i++) {
        $user = User::firstOrCreate(
            ['email' => 'user'.$i.'@example.com'],
            [
                'user_name' => 'user'.$i,
                'first_name' => 'User',
                'last_name' => $i,
                'password' => Hash::make('password'),
                'type' => 'customer',
                'phone_number' => '123456789'.$i,
                'gender' => $i % 2 ? 'male' : 'female'
            ]
        );
$j=$i-1;
        $card = Card::create([
            'card_number' => $this->generateCardNumber($j),
            'expire_time' =>$i.'/29',
            'cvv' => $this->generateCvv($j),
            'card_holder' => 'card_holder'.$j,
        ]);

        Wallet::create([
            'user_id' => $i,
            'card_id' => $card->id,
            'balance' => rand(1000, 10000) / 100,
        ]);
    }
}

    // توليد رقم بطاقة فريد
 private function generateCardNumber($digit): string
{
    // التأكد أن المدخل هو رقم واحد بين 0-9
    $singleDigit = substr($digit, 0, 1);

    // إنشاء الرقم المكون من 16 خانة متطابقة
    $cardNumber = str_repeat($singleDigit, 16);

    return $cardNumber;
}

 private function generateCvv($digit): string
{
    // التأكد أن المدخل هو رقم واحد بين 0-9
    $singleDigit = substr($digit, 0, 1);

    // إنشاء الرقم المكون من 16 خانة متطابقة
    $cardNumber = str_repeat($singleDigit, 3);

    return $cardNumber;
}
    // توليد تاريخ انتهاء صالح
    private function generateExpiryDate(): string
    {
        $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = str_pad(rand(25, 30), 2, '0', STR_PAD_LEFT); // 2025-2030
        return $month . '/' . $year;
    }
}
