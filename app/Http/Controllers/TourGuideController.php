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

public function login(Request $request)
{
    try {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

        switch ($user->type) {
            case 'guide':


                 if (!$user->tourGuide) {
        return response()->json([
            'message' => 'Guide profile not completed'
        ], 403);
    }

    if ($user->tourGuide->confirmByAdmin == 2) {
        return response()->json([
            'message' => 'Please wait while the admin reviews your application'
        ], 403);
    }
    elseif ($user->tourGuide->confirmByAdmin == 0) {
        return response()->json([
            'message' => 'We regret to inform you that your application has been rejected'
        ], 403);
    }

                $token = $user->createToken('tour_guide_auth_token')->plainTextToken;
                $user->generateCode();

                return response()->json([
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'user_name' => $user->user_name,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'type' => $user->type,
                        'phone_number' => $user->phone_number,
                        'gender' => $user->gender,
                        'profile_image' => $user->guide_picture_path ? asset('storage/' . $user->guide_picture_path) : null,
                        'birth_date' => $user->birth_date,
                        'years_of_experience' => $user->tourGuide->years_of_experience,
                        'languages' => $user->tourGuide->languages,
                        'license_picture_path' => $user->tourGuide->license_picture_path,
                        'cv_path' =>$user->tourGuide->cv_path,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 200);

            case 'tourist':
                $token = $user->createToken('tourist_auth_token')->plainTextToken;
                $user->generateCode();

                return response()->json([
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'user_name' => $user->user_name,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'type' => $user->type,
                        'phone_number' => $user->phone_number,
                        'gender' => $user->gender,
                        'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                        'birth_date' => $user->birth_date,
                        'nationality' => $user->tourist->nationality ?? null,
                     
                        'emergency_contact' => $user->tourist->emergency_contact ?? null,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 200);

            default:
                return response()->json([
                    'message' => 'Invalid user type'
                ], 401);
        }

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
    public function registerTourGuide(Request $request)
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
                'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',//nullable

                'type' => 'nullable|string',
                'gender' => 'required|in:male,female',
                'birth_date' => 'required|date',//2

                //tourguide
                'languages' => 'required|string',
                'years_of_experience' => 'required|string',
                'license_picture_path' => 'required|image|mimes:png,jpeg|max:2048',
                'cv_path' => 'required|file|mimes:pdf|max:5120', // ملف PDF بحد أقصى 5MB
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
                        'user' => $user,
                        'tourGuide' => $tourGuide,


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
//for delete
// public function loginTourGuide(Request $request)
//     {
//         try {
//             $validatedData = $request->validate([
//                 'email' => 'required|string|email',
//                 'password' => 'required|string'
//             ]);

//          $user = User::where('email', $request->email)->first();

// if (!$user) {
//     return response()->json([
//         'message' => 'Invalid email'
//     ], 401);
// }
//    if (!Hash::check($request->password, $user->password)) {

//             return response()->json([
//                 'message' => 'Invalid password'
//             ], 401);
//         }

// switch ($user->type) {
//     case 'guide':
//           // إنشاء توكن جديد
//             $token = $user->createToken('tour_guide_auth_token')->plainTextToken;
//             $user->generateCode();

//         break;

//     case 'tourist':

//         break;

//     default:
//         return response()->json([
//             'message' => 'Invalid user type'
//         ], 401);
// }



//             // $tourGuide->notify(new TwoFactorCode());


//             return response()->json([
//                 'message' => 'Login successful',
//                 'tour_guide' => [
//                     //user
//                     'id' => $tourGuide->id,
//                     'user_name' => $tourGuide->user_name,
//                     'name' => $tourGuide->first_name . ' ' . $tourGuide->last_name,
//                     'email' => $tourGuide->email,
//                     'type' => $tourGuide->type,
//                     'phone_number' => $tourGuide->phone_number,
//                     'gender' => $tourGuide->gender,
//                     'profile_image' => $tourGuide->guide_picture_path ? asset('storage/' . $tourGuide->guide_picture_path) : null,
//                     'birth_date' => $tourGuide->birth_date,
//                     //tour guide
//                     'years_of_experience' => $tourGuide->years_of_experience,
//                     'languages' => $tourGuide->languages,
//                     'license_picture_path' => $tourGuide->license_picture_path,
//                     'cv_path' => $tourGuide->cv_path,
//                 ],
//                 'access_token' => $token,
//                 'token_type' => 'Bearer'
//             ], 200);

//         } catch (\Illuminate\Validation\ValidationException $e) {
//             return response()->json([
//                 'message' => 'Validation failed',
//                 'errors' => $e->errors()
//             ], 422);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'message' => 'Login failed',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }

    // public function loginTourGuide(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'email' => 'required|string|email',
    //             'password' => 'required|string'
    //         ]);

    //         // البحث عن المرشد السياحي
    //         $tourGuide = User::where('email', $request->email)->first();
    //         if (!$tourGuide) {
    //             return response()->json([
    //                 'message' => 'Invalid email'
    //             ], 401);
    //         }
    //         // if (!$tourGuide || $tourGuide->type != 'guide') {
    //         //     return response()->json([
    //         //         'message' => 'You are not a tourist'
    //         //     ], 401);
    //         // }
    //         // التحقق من وجود المرشد وصحة كلمة المرور

    //         if ($tourGuide && !Hash::check($request->password, $tourGuide->password)) {
    //             return response()->json([
    //                 'message' => 'Invalid password'
    //             ], 401);
    //         }

    //         // إنشاء توكن جديد
    //         $token = $tourGuide->createToken('tour_guide_auth_token')->plainTextToken;
    //         $tourGuide->generateCode();

    //         // $tourGuide->notify(new TwoFactorCode());


    //         return response()->json([
    //             'message' => 'Login successful',
    //             'tour_guide' => [
    //                 //user
    //                 'id' => $tourGuide->id,
    //                 'user_name' => $tourGuide->user_name,
    //                 'name' => $tourGuide->first_name . ' ' . $tourGuide->last_name,
    //                 'email' => $tourGuide->email,
    //                 'type' => $tourGuide->type,
    //                 'phone_number' => $tourGuide->phone_number,
    //                 'gender' => $tourGuide->gender,
    //                 'profile_image' => $tourGuide->guide_picture_path ? asset('storage/' . $tourGuide->guide_picture_path) : null,
    //                 'birth_date' => $tourGuide->birth_date,
    //                 //tour guide
    //                 'years_of_experience' => $tourGuide->years_of_experience,
    //                 'languages' => $tourGuide->languages,
    //                 'license_picture_path' => $tourGuide->license_picture_path,
    //                 'cv_path' => $tourGuide->cv_path,
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
