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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                        'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
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

             $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $fileName = uniqid('profile_') . '.' . $image->getClientOriginalExtension();
            $profilePicturePath = $image->storeAs('img_prof', $fileName, 'public');
        }


        $relativeLicensePath = null;
        if ($request->hasFile('license_picture_path')) {
            $image = $request->file('license_picture_path');
            $fileName = uniqid('licensePath_') . '.' . $image->getClientOriginalExtension();
            $relativeLicensePath = $image->storeAs('licensePath', $fileName, 'public');
        }

          $relativeCvPath = null;
        if ($request->hasFile('cv_path')) {
            $image = $request->file('cv_path');
            $fileName = uniqid('cv_path') . '.' . $image->getClientOriginalExtension();
            $relativeCvPath = $image->storeAs('cv', $fileName, 'public');
        }


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
 $imageUrl = $profilePicturePath
            ? asset('storage/' . $profilePicturePath)
            : null;
 $LicensePatheUrl = $relativeLicensePath
            ? asset('storage/' . $relativeLicensePath)
            : null;
            $relativeCvPathPatheUrl = $relativeCvPath
            ? asset('storage/' . $relativeCvPath)
            : null;

            return response()->json(
                [
                    [
                        'message' => 'Tour guide registered successfully',
                        'user' => $user,
                        'tourGuide' => $tourGuide,


                    ],
                    [
                         'profile_picture_url' => $imageUrl,
                         'LicensePatheUrl' =>  $LicensePatheUrl,
                          'relativeCvPathPatheUrl' =>  $relativeCvPathPatheUrl,
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
 public function getProfile(Request $request)
    {
        $user = $request->user();
        $tourGuide = $user->tourGuide;

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

        // إذا كان المستخدم مرشداً سياحياً
        if ($tourGuide) {
            $response = array_merge($response, [
                'languages' => $tourGuide->languages,
                'years_of_experience' => $tourGuide->years_of_experience,
                'license_picture_url' => $tourGuide->license_picture_path ? asset('storage/'.$tourGuide->license_picture_path) : null,
                'cv_url' => $tourGuide->cv_path ? asset('storage/'.$tourGuide->cv_path) : null,
                'confirmByAdmin' => $tourGuide->confirmByAdmin
            ]);
        }

        return response()->json([
            'message' => 'تم جلب بيانات الملف الشخصي بنجاح',
            'data' => $response
        ]);
    }

    // تحديث البيانات الأساسية
 public function updateProfile(Request $request)
{
    $user = Auth::user();
    $changesDetected = false;
    $passwordChanged = false;

    $validator = Validator::make($request->all(), [
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'phone_number' => 'sometimes|string|max:20',
        'gender' => 'sometimes|string|in:male,female,other',
        'birth_date' => 'sometimes|date',
        'languages' => 'sometimes|string',
        'years_of_experience' => 'sometimes|string',
        'new_password' => 'nullable|sometimes|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    // Handle password update (without requiring current password)
    if ($request->filled('new_password')) {
        if (!Hash::check($request->new_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $passwordChanged = true;
            $changesDetected = true;
        }
    }

    // Update user basic info
    $userData = $request->only(['first_name', 'last_name', 'phone_number', 'gender', 'birth_date']);
    foreach ($userData as $key => $value) {
        if ($user->$key != $value) {
            $user->$key = $value;
            $changesDetected = true;
        }
    }

    if ($changesDetected) {
        $user->save();
    }

    // Update tour guide info if user is a guide
    if ($user->tourGuide) {
        $guideData = $request->only(['languages', 'years_of_experience']);
        foreach ($guideData as $key => $value) {
            if ($user->tourGuide->$key != $value) {
                $user->tourGuide->$key = $value;
                $changesDetected = true;
            }
        }
        if ($changesDetected) {
            $user->tourGuide->save();
        }
    }

    if (!$changesDetected) {
        return response()->json([
            'message' => 'No data has changed. Everything is already up to date.'
        ], 200);
    }

    $profileImage = $user->profile_image;
    $imageUrl = $profileImage ? asset('storage/' . $profileImage) : null;

    return response()->json([
        'message' => 'Profile updated successfully.',
        'update_password'=>   ($passwordChanged ? ' Password updated successfully.' : 'No new password was entered so the password was not changed.'),
        'user' => $user->load('tourGuide'),
        'url_profile_img' => $imageUrl
    ], 200);
}


    // تحديث صورة الملف الشخصي
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // حذف الصورة القديمة إذا كانت موجودة
        // if ($user->profile_image) {
        //     $oldImagePath = 'public/' . $user->profile_image;
        //     Storage::delete($oldImagePath);
        // }

        // حفظ الصورة الجديدة
        $image = $request->file('profile_image');
        $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('img_prof', $imageName, 'public');


        // تحديث قاعدة البيانات
        $user->profile_image = 'img_prof/' . $imageName;
        $user->save();

        return response()->json([
            'message' => 'تم تحديث الصورة بنجاح',
            'profile_image_url' => asset(Storage::url($imagePath))
        ]);
    }


}
