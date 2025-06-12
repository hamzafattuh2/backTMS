<?php

namespace App\Http\Controllers;
use App\Models\Wallet;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CardController extends Controller
{
    public function store(Request $request)
    {
        // 1. التحقق من البيانات

        $validator = Validator::make($request->all(), [
    'card_number' => 'required|digits:16|unique:cards',
    'expire_time' => [
        'required',
        'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'
    ],
    'cvv' => 'required|digits:3',
    'card_holder' => 'required|string|max:255',
    'wallet_id' => 'required|exists:wallets,id'
]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. التحقق من عدم التكرار
        $exists = Card::where('card_number', $request->card_number)
            ->orWhere('cvv', $request->cvv)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'البطاقة مسجلة مسبقاً'], 409);
        }

        // 3. إنشاء البطاقة
        $card = Card::create($request->only([
            'card_number',
            'expire_time',
            'cvv',
            'card_holder'
        ]));

        // 4. ربط البطاقة بالمحفظة
        $wallet = Wallet::find($request->wallet_id);
        $wallet->card_id = $card->id;
        $wallet->save();

        return response()->json([
            'message' => 'تمت إضافة البطاقة بنجاح',
            'card' => $card
        ], 201);
    }


public function verifyCard(Request $request)
{
    $validator = Validator::make($request->all(), [
        'card_number' => 'required|digits:16',
        'expire_time' => [
            'required',
            'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
            function ($attribute, $value, $fail) {
                $parts = explode('/', $value);
                $month = $parts[0];
                $year = '20'.$parts[1];

                if (now()->gt(Carbon::create($year, $month)->endOfMonth())) {
                    $fail('انتهت صلاحية البطاقة');
                }
            }
        ],
        'cvv' => 'required|digits:3',
        'card_holder' => 'required|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'valid' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // تحقق من رقم البطاقة باستخدام خوارزمية لون
    if (!$this->isValidLuhn($request->card_number)) {
        return response()->json([
            'valid' => false,
            'message' => 'رقم البطاقة غير صالح'
        ], 422);
    }

    // البحث عن البطاقة في قاعدة البيانات
    $card = DB::table('cards') // استبدل 'cards' باسم جدولك
              ->where('card_number', $request->card_number)
              ->first();

    // التحقق من وجود البطاقة وتطابق اسم الحامل
    if (!$card) {
        return response()->json([
            'valid' => false,
            'message' => 'البطاقة غير مسجلة في النظام'
        ], 422);
    }

    if (strtolower(trim($card->card_holder)) !== strtolower(trim($request->card_holder))) {
        return response()->json([
            'valid' => false,
            'message' => 'اسم حامل البطاقة غير متطابق مع المسجل'
        ], 422);
    }
$card_id = $card->id;
// $balance = $wallet->balance;
// $balance = $wallet->balance;
$wallet = DB::table('wallets')->where('id',$card_id)->first();

    return response()->json([
        'valid' => true,
        'message' => 'بيانات البطاقة صالحة'
        ,'balance'=>$wallet->balance
        ,'id'=>$wallet->id
        ,'wallet'=>$wallet
    ]);
}

private function isValidLuhn($number)
{
    $sum = 0;
    $numDigits = strlen($number);
    $parity = $numDigits % 2;

    for ($i = 0; $i < $numDigits; $i++) {
        $digit = $number[$i];
        if ($i % 2 == $parity) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }

    return ($sum % 10) == 0;
}
}
