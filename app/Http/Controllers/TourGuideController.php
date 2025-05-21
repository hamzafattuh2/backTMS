<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\Notifications\TwoFactorCode;
use App\Models\TourGuide;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\BroadcastToGuides;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class TourGuideController extends Controller
{

    public function registerTourGuide(Request $request) {
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
                'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',//nullable

                'type' => 'nullable|string',
                'gender' => 'required|in:male,female',
                'birth_date' => 'required|date',//2

                //tourguide
                'languages' => 'required|string',
                'years_of_experience' => 'required|string',
                'license_picture_path' => 'required|image|mimes:png,jpeg|max:2048',
                'cv_path' => 'required|file|mimes:pdf|max:5120', // ملف PDF بحد أقصى 5MB
                // 'guide_picture_path' => 'required|image|mimes:jpeg,png|max:2048', // صورة بحد أقصى 2MB
            ]);

            if ($request->hasFile('profile_image')) {
    $profilePath = $request->file('profile_image')->store('public/profile_images');
    $profilePicturePath = str_replace('public/', '', $profilePath);
} else {
    $profilePicturePath = null;
}
            // تخزين الصورة
            $licensePath = $request->file('license_picture_path')->store('public/license_picture_path');
            $relativeLicensePath = str_replace('public/', '', $licensePath);

            $cvPath = $request->file('cv_path')->store('public/cvs');
            $relativeCvPath = str_replace('public/', '', $cvPath);

            // $guidePicturePath = $request->file('guide_picture_path')->store('public/guide_pictures');
            // $relativeGuidePicturePath = str_replace('public/', '', $guidePicturePath);

            //add new user
            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'type' => 'guide',
                'phone_number' => $validatedData['phone_number'],
                'profile_image' => $profilePicturePath ?? null,
                'gender' => $validatedData['gender'],
                'birth_date' => $validatedData['birth_date'] ?? null,
            ]);
            $token = $user->createToken('tour_guide_auth_token')->plainTextToken;
            $user->generateCode();
            $user->notify(new TwoFactorCode());


            $tourGuide = TourGuide::create([
                'user_id' => $user->id,
                'languages' => $validatedData['languages'],
                'years_of_experience' => $validatedData['years_of_experience'],
                'license_picture_path' => $relativeLicensePath,
                'cv_path' => $relativeCvPath, // تمت إضافة هذا الحقل
                // 'guide_picture_path' => $relativeGuidePicturePath, // تمت إضافة هذا الحقل
                'confirmByAdmin' => false,
            ]);

            $wallet = Wallet::create(
                [
                    'user_id' => $user->id,
                    'balance' => 0,
                ]
            );

            return response()->json(
                [
                    [
                        'message' => 'Tour guide registered successfully',
                        'user'=>$user ,
                        'tourGuide' => $tourGuide,


                        // 'license_picture_url' => asset('storage/' . $relativeLicensePath),
                    ],
                    [
                        'access_token' => $token,
                        'token_type' => 'Bearer'
                    ]
                ],
                201
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function loginTourGuide(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            // البحث عن المرشد السياحي
            $tourGuide = User::where('email', $request->email)->first();
             if (!$tourGuide) {
                return response()->json([
                    'message' => 'Invalid email'
                ], 401);
            }
            // if (!$tourGuide || $tourGuide->type != 'guide') {
            //     return response()->json([
            //         'message' => 'You are not a tourist'
            //     ], 401);
            // }
            // التحقق من وجود المرشد وصحة كلمة المرور

            if ( $tourGuide && !Hash::check($request->password, $tourGuide->password)) {
                return response()->json([
                    'message' => 'Invalid password'
                ], 401);
            }

            // إنشاء توكن جديد
            $token = $tourGuide->createToken('tour_guide_auth_token')->plainTextToken;
            $tourGuide->generateCode();

            // $tourGuide->notify(new TwoFactorCode());


            return response()->json([
                'message' => 'Login successful',
                'tour_guide' => [
                    //user
                    'id' => $tourGuide->id,
                    'user_name' => $tourGuide->user_name,
                    'name' => $tourGuide->first_name . ' ' . $tourGuide->last_name,
                    'email' => $tourGuide->email,
                    'type' => $tourGuide->type,
                    'phone_number' => $tourGuide->phone_number,
                    'gender' => $tourGuide->gender,
                    'profile_image' => $tourGuide->guide_picture_path ? asset('storage/' . $tourGuide->guide_picture_path) : null,
                    'birth_date' => $tourGuide->birth_date,
                    //tour guide
                    'years_of_experience' => $tourGuide->years_of_experience,
                    'languages' => $tourGuide->languages,
                    'license_picture_path' => $tourGuide->license_picture_path,
                    'cv_path' => $tourGuide->cv_path,
                    // 'guide_picture_path' => $tourGuide->guide_picture_path

                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function logoutTourGuide(Request $request)
    {
        try {
            $tourGuide = $request->user();

            if (!$tourGuide) {
                return response()->json([
                    'message' => 'No authenticated user found',
                    'status' => 'error'
                ], 401);
            }

            // حذف التوكن الحالي فقط
            $tourGuide->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful',
                'data' => [
                    'id' => $tourGuide->id,
                    'name' => $tourGuide->first_name . ' ' . $tourGuide->last_name,
                    'email' => $tourGuide->email
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    public function checkGuideConfirmation()
    {
        $user = auth()->user();

        $guide = $user->tourGuide; // استخدام العلاقة one-to-one
        if (!$guide) {
            return response()->json(['error' => 'Guide profile not found'], 404);
        }

        return response()->json([
            'message' => 'ahlen',
            'confirm by admin ' => $guide->confirmByAdmin ? "yes" : "no",
        ], 201);

    }
    public function notifyAllGuides(Request $request): JsonResponse
    {
        // 1) التحقق من البيانات
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:1000',
            'language' => 'nullable|string|max:1000'

        ]);

        // 2) جلب كل المرشدين (Users حيث type = 'guide')
        // $guides = User::where('type', 'guide')->get();
        $guides = TourGuide::where('languages', $data['language'])->get();



        if ($guides->isEmpty()) {
            return response()->json(['message' => 'لا يوجد مرشدون لإرسال الإشعار.'], 404);
        }

        // 3) إرسال الإشعار دفعةً واحدة (broadcast + database)
        Notification::send($guides, new BroadcastToGuides($data['title'], $data['body']));

        return response()->json([
            'message' => 'تم إرسال الإشعار بنجاح إلى جميع المرشدين.',
            'guides_notified' => $guides->count(),
        ], 200);
    }
}
