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
    public function registerTourist(Request $request)
    {
        try {
            // التحقق من البيانات بما فيها الصورة
            $validatedData = $request->validate([

                //user
                'user_name' => 'required|string|max:50|unique:users,user_name',
                'last_name' => 'required|string|max:50',
                'first_name' => 'required|string|max:50',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:20',
                'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
                'type' => 'nullable|string',
                'gender' => 'required|in:male,female',
                'birth_date' => 'required|date',//2



                //tourist
                'nationality' => 'required|string|max:100',//1
                'special_needs' => 'nullable|string',//3


            ]);


            // بدء المعاملة لضمان سلامة البيانات
            DB::beginTransaction();

            // تخزين صورة الملف الشخصي إذا وجدت
            $profilePicturePath = null;
            if ($request->hasFile('profile_image')) {
                $path = $request->file('profile_image')->store('public/profile_image');
                $profilePicturePath = str_replace('public/', '', $path);
            }

            // إنشاء المستخدم في جدول users
            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'type' => 'tourist',
                'phone_number' => $validatedData['phone_number'],
                'profile_image' => $profilePicturePath ?? null,
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birth_date'] ?? null,

            ]);

            $token = $user->createToken('tourist_auth_token')->plainTextToken;
            $user->generateCode();

            // $user->notify(new TwoFactorCode());
            // إنشاء السائح في جدول tourists
            $tourist = Tourist::create([
                'user_id' => $user->id,
                'nationality' => $validatedData['nationality'],
                'special_needs' => $validatedData['special_needs'] ?? null,
            ]);
            $wallet = Wallet::create(
                [
                    'user_id' => $user->id,
                    'balance' => 0,
                ]
            );
            // إتمام المعاملة
            DB::commit();

            return response()->json(
                [
                    [
                        'message' => 'Tourist registered successfully',
                        'user' => $user,
                        'tourist' => $tourist,
                        'profile_picture_url' => $profilePicturePath ? asset('storage/' . $profilePicturePath) : null
                    ],
                    [
                        'access_token' => $token,
                        'token_type' => 'Bearer'
                    ]
                ],
                201
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //for delete
    // public function loginTourist(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'email' => 'required|string|email',
    //             'password' => 'required|string'
    //         ]);

    //         // البحث عن المرشد السياحي
    //         $tourist = User::where('email', $request->email)->first();
    //         if (!$tourist || $tourist->type != 'tourist') {
    //             return response()->json([
    //                 'message' => 'You are not a tourist'
    //             ], 401);
    //         }
    //         // التحقق من وجود المرشد وصحة كلمة المرور
    //         if (!$tourist || !Hash::check($request->password, $tourist->password)) {
    //             return response()->json([
    //                 'message' => 'Invalid email or password'
    //             ], 401);
    //         }



    //         // إنشاء توكن جديد
    //         $token = $tourist->createToken('tourist_auth_token')->plainTextToken;
    //         $tourist->generateCode();

    //         // $tourist->notify(new TwoFactorCode());

    //         return response()->json([
    //             'message' => 'Login successful',
    //             'tour_guide' => [
    //                 //user
    //                 'id' => $tourist->id,
    //                 'user_name' => $tourist->user_name,
    //                 'name' => $tourist->first_name . ' ' . $tourist->last_name,
    //                 'email' => $tourist->email,
    //                 'type' => $tourist->type,
    //                 'phone_number' => $tourist->phone_number,
    //                 'gender' => $tourist->gender,
    //                 'profile_image' => $tourist->guide_picture_path ? asset('storage/' . $tourist->guide_picture_path) : null,
    //                 'birth_date' => $tourist->birth_date,
    //                 //tourist
    //                 //     , 'nationality'=>$tourist->nationality,
    //                 //    , 'special_needs'=>$tourist->special_needs,
    //                 //    , 'emergency_contact'=>$tourist->emergency_contact,

    //             ],
    //             'access_token' => $token,
    //             'token_type' => 'Bearer'
    //         ], 200);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Login failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    // public function logoutTourist(Request $request)
    // {
    //     $user = $request->user();
    //     $user->currentAccessToken()->delete();
    //     return response()->json(['message' => 'Logout successful']);
    // }

// public function updateProfile(Request $request)
// {
//     $user = $request->user();

//     $validated = $request->validate([
//         'new_password' => 'sometimes|string|min:8|confirmed',
//         'user_name' => 'sometimes|string|max:50|unique:users,user_name,'.$user->id,
//         'first_name' => 'required  |string|max:50',
//         'last_name' => 'sometimes|string|max:50',
//         'phone_number' => 'sometimes|string|max:20',
//         'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
//         'gender' => 'sometimes|in:male,female',
//         'birth_date' => 'sometimes|date',
//         'nationality' => 'sometimes|string|max:100',
//         'special_needs' => 'nullable|string',
//     ]);

//     if ($request->has('new_password')) {
//         $validated['password'] = Hash::make($validated['new_password']);
//         unset($validated['new_password']);
//     }

//     if ($request->hasFile('profile_image')) {
//         $path = $request->file('profile_image')->store('public/profile_images');
//         $validated['profile_image'] = str_replace('public/', '', $path);

//         if ($user->profile_image) {
//             Storage::delete('public/'.$user->profile_image);
//         }
//     }
//     $user->update([
//         'first_name' => $validated['first_name'] ?? $user->first_name,
//         'last_name' => $validated['last_name'] ?? $user->last_name,
//         'password' => $validated['password'] ?? $user->password,
//         'phone_number' => $validated['phone_number'] ?? $user->phone_number,
//         'profile_image' => $validated['profile_image'] ?? $user->profile_image,
//         'gender' => $validated['gender'] ?? $user->gender,
//         'birth_date' => $validated['birth_date'] ?? $user->birth_date,
//     ]);
//     // $user->update($validated);

//     // if ($user->tourist) {
//     //     $user->tourist->update($validated);
//     // }
//     if ($user->tourist) {
//         $user->tourist->update([
//             'nationality' => $validated['nationality'] ?? $user->tourist->nationality,
//             'special_needs' => $validated['special_needs'] ?? $user->tourist->special_needs,
//         ]);
//     }

//     return response()->json([
//         'message' => 'تم تحديث الملف الشخصي بنجاح',
//         'user' => $user->fresh()
//     ]);
// }

//


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
        'special_needs' => 'nullable|string',
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
        'special_needs' => $textData['special_needs'] ?? null,
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
        $response['special_needs'] = $user->tourist->special_needs;
    }

    return response()->json([
        'message' => 'تم جلب بيانات الملف الشخصي بنجاح',
        'data' => $response
    ]);
}
}
