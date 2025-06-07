<?php

namespace App\Http\Controllers;
use App\Models\Tourist;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB; // أضف هذا السطر
use Illuminate\Support\Facades\Hash; // أضف هذا السطر
use Illuminate\Http\Request;
use App\Notifications\TwoFactorCode;
use Illuminate\Support\Facades\Storage;

class TouristController extends Controller
{
    // public function registerTourist(Request $request)
    // {
    //     try {
    //         // التحقق من البيانات بما فيها الصورة
    //         $validatedData = $request->validate([

    //             //user
    //             'user_name' => 'required|string|max:50|unique:users,user_name',
    //             'last_name' => 'required|string|max:50',
    //             'first_name' => 'required|string|max:50',
    //             'email' => 'required|string|email|max:255|unique:users,email',
    //             'password' => 'required|string|min:8|confirmed',
    //             'phone_number' => 'required|string|max:20',
    //             'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
    //             'type' => 'nullable|string',
    //             'gender' => 'required|in:male,female',
    //             'birth_date' => 'required|date',//2



    //             //tourist
    //             'nationality' => 'required|string|max:100',//1



    //         ]);


    //         // بدء المعاملة لضمان سلامة البيانات
    //         DB::beginTransaction();

    //         // تخزين صورة الملف الشخصي إذا وجدت
    //         $profilePicturePath = null;
    //     if ($request->hasFile('profile_image')) {
    //         $path = $request->file('profile_image')->store(
    //             'public/img_prof', // المسار الجديد
    //             'public' // استخدام نظام الملفات public
    //         );
    //         $profilePicturePath = str_replace('public/', '', $path);
    //     }

    //         // إنشاء المستخدم في جدول users
    //         $user = User::create([
    //             'user_name' => $validatedData['user_name'],
    //             'first_name' => $validatedData['first_name'],
    //             'last_name' => $validatedData['last_name'],
    //             'email' => $validatedData['email'],
    //             'password' => Hash::make($validatedData['password']),
    //             'type' => 'tourist',
    //             'phone_number' => $validatedData['phone_number'],
    //             'profile_image' => $profilePicturePath ?? null,
    //             'gender' => $validatedData['gender'],
    //             'birth_date' => $validatedData['birth_date'] ?? null,

    //         ]);

    //         $token = $user->createToken('tourist_auth_token')->plainTextToken;
    //         $user->generateCode();

    //         // $user->notify(new TwoFactorCode());
    //         // إنشاء السائح في جدول tourists
    //         $tourist = Tourist::create([
    //             'user_id' => $user->id,
    //             'nationality' => $validatedData['nationality'],

    //         ]);
    //         $wallet = Wallet::create(
    //             [
    //                 'user_id' => $user->id,
    //                 'balance' => 0,
    //             ]
    //         );
    //         // إتمام المعاملة
    //         DB::commit();

    //         return response()->json(
    //             [
    //                 [
    //                     'message' => 'Tourist registered successfully',
    //                     'user' => $user,
    //                     'tourist' => $tourist,
    //                    'profile_picture_url' => $profilePicturePath
    //                 ? asset("storage/img_prof/" . basename($profilePicturePath))
    //                 : null    ],
    //                 [
    //                     'access_token' => $token,
    //                     'token_type' => 'Bearer'
    //                 ]
    //             ],
    //             201
    //         );

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Registration failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    //for delete

public function registerTourist(Request $request)
{
    try {
        // ✅ التحقق من البيانات
        $validatedData = $request->validate([
            // بيانات المستخدم
            'user_name' => 'required|string|max:50|unique:users,user_name',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',

            // بيانات السائح
            'nationality' => 'required|string|max:100',
        ]);

        DB::beginTransaction();

        // ✅ معالجة ورفع صورة الملف الشخصي (إن وجدت)
        $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $fileName = uniqid('profile_') . '.' . $image->getClientOriginalExtension();
            $profilePicturePath = $image->storeAs('img_prof', $fileName, 'public');
        }

        // ✅ إنشاء المستخدم
        $user = User::create([
            'user_name' => $validatedData['user_name'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => 'tourist',
            'phone_number' => $validatedData['phone_number'],
            'profile_image' => $profilePicturePath,
            'gender' => $validatedData['gender'],
            'birth_date' => $validatedData['birth_date'],
        ]);

        // ✅ إنشاء رمز التوكن
        $token = $user->createToken('tourist_auth_token')->plainTextToken;
        $user->generateCode();

        // ✅ إنشاء السائح
        $tourist = Tourist::create([
            'user_id' => $user->id,
            'nationality' => $validatedData['nationality'],
        ]);

        // ✅ إنشاء المحفظة
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        DB::commit();

        // ✅ تحضير رابط الصورة (إن وجد)
        $imageUrl = $profilePicturePath
            ? asset('storage/' . $profilePicturePath)
            : null;

        return response()->json([
            'message' => 'Tourist registered successfully',
            'user' => $user,
            'tourist' => $tourist,
            'profile_picture_url' => $imageUrl,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Registration failed',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function updateProfile(Request $request)
{
    $user = $request->user();

    // تحقق من البيانات النصية
    $textData = $request->validate([
        'new_password' => 'sometimes|string|min:8',
        'new_password_confirmation' => 'required_with:new_password|same:new_password',
        'user_name' => 'sometimes|string|max:50|unique:users,user_name,' . $user->id,
        'first_name' => 'sometimes|string|max:50',
        'last_name' => 'sometimes|string|max:50',
        'phone_number' => 'sometimes|string|max:20',
        'gender' => 'sometimes|in:male,female',
        'birth_date' => 'sometimes|date',
        'nationality' => 'sometimes|string|max:100',
    ]);

    $updatedFields = [];

    // معالجة كلمة السر
    $passwordUpdated = false;
    if ($request->has('new_password')) {
        $textData['password'] = Hash::make($textData['new_password']);
        unset($textData['new_password']);
        $passwordUpdated = true;
    }

    // معالجة الصورة بشكل منفصل
    if ($request->hasFile('profile_image')) {
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png|max:2048'
        ]);

        $path = $request->file('profile_image')->store('public/profile_images');
        $textData['profile_image'] = str_replace('public/', '', $path);

        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
        }
    }

    // تجهيز بيانات التحديث
    $userData = array_filter([
        'first_name' => $textData['first_name'] ?? null,
        'last_name' => $textData['last_name'] ?? null,
        'password' => $textData['password'] ?? null,
        'phone_number' => $textData['phone_number'] ?? null,
        'profile_image' => $textData['profile_image'] ?? null,
        'gender' => $textData['gender'] ?? null,
        'birth_date' => $textData['birth_date'] ?? null,
    ]);

    $touristData = array_filter([
        'nationality' => $textData['nationality'] ?? null,
    ]);

    // تحقق إذا لا يوجد أي شيء لتحديثه
    if (empty($userData) && empty($touristData)) {
        return response()->json([
            'message' => 'Nothing to update.'
        ], 200);
    }

    // تحديث بيانات المستخدم
    if (!empty($userData)) {
        $user->update($userData);
        $updatedFields['user'] = true;
    }

    // تحديث بيانات السائح
    if ($user->tourist && !empty($touristData)) {
        $user->tourist->update($touristData);
        $updatedFields['tourist'] = true;
    }

    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $user->fresh(),
        'tourist_data' => $user->tourist ? $user->tourist->fresh() : null,
        'password' => $passwordUpdated ? 'Password updated successfully.' : null
    ]);
}

public function getProfile(Request $request)
{
    $user = $request->user();

    $response = [
        'user_name' => $user->user_name,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'phone_number' => $user->phone_number,
        'profile_image' => $user->profile_image ? asset('storage/'.$user->profile_image) : null,
        'gender' => $user->gender,
        'birth_date' => $user->birth_date,
    ];

    // إذا كان المستخدم سائحاً، نضيف حقول السياح
    if ($user->tourist) {
        $response['nationality'] = $user->tourist->nationality;
    }

    return response()->json([
        'message' => 'تم جلب بيانات الملف الشخصي بنجاح',
        'data' => $response
    ]);
}
}
