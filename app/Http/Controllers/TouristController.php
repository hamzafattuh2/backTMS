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
use Illuminate\Support\Facades\Auth;

class TouristController extends Controller
{


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
    $validatedData = $request->validate([
        'new_password' => 'nullable|sometimes|string|min:8',
        'new_password_confirmation' => 'required_with:new_password|same:new_password',
        'user_name' => 'sometimes|string|max:50|unique:users,user_name,' . $user->id,
        'first_name' => 'sometimes|string|max:50',
        'last_name' => 'sometimes|string|max:50',
        'phone_number' => 'sometimes|string|max:20',
        'gender' => 'sometimes|in:male,female',
        'birth_date' => 'sometimes|date',
        'nationality' => 'sometimes|string|max:100',
        'profile_image' => 'sometimes|image|mimes:jpeg,png|max:2048'
    ]);

    $passwordUpdated = false;
    $imageUrl = null;

    // معالجة كلمة السر
    if ($request->has('new_password')) {
        $user->password = Hash::make($validatedData['new_password']);
        $passwordUpdated = true;
    }

    $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $fileName = uniqid('profile_') . '.' . $image->getClientOriginalExtension();
            $profilePicturePath = $image->storeAs('img_prof', $fileName, 'public');
        }

    // تحديث البيانات الأساسية
    $user->fill($request->only([
        'user_name',
        'first_name',
        'last_name',
        'phone_number',
        'gender',
        'birth_date'
    ]));

    // تحديث بيانات السائح إذا كانت موجودة
    $touristUpdated = false;
    if ($user->tourist && $request->has('nationality')) {
        $user->tourist->nationality = $validatedData['nationality'];
        $user->tourist->save();
        $touristUpdated = true;
    }
 $imageUrl = $profilePicturePath
            ? asset('storage/' . $profilePicturePath)
            : null;

    // حفظ التحديثات
    $user->save();

    return response()->json([
        'message' => 'Profile updated successfully.',
        'data' => [
            'user' => $user->fresh(),
            'tourist' => $user->tourist ? $user->tourist->fresh() : null,
            'profile_picture_url' => $imageUrl ?: $user->profile_image_url,
        ],
        'password_updated' => $passwordUpdated,
        'tourist_updated' => $touristUpdated
    ]);
}
//   public function getProfileImage()
//     {
//         $user = Auth::user();

//         if (!$user || !$user->profile_image) {
//             return response()->json([
//                 'message' => 'الصورة غير متوفرة',
//                 'profile_image' => $user->profile_image,
//             ], 404);
//         }

//         $imagePath = '/img_prof/' . $user->profile_image;

//         if (!Storage::exists($imagePath)) {
//             return response()->json([
//                 'message' => 'الصورة غير موجودة',
//                 // 'profile_image_url' => null
//                                'profile_image' => $user->profile_image,
//             ], 404);
//         }

//         return response()->json([
//             'message' => 'تم جلب الصورة بنجاح',
//             'profile_image_url' => asset(Storage::url($imagePath))
//         ]);
//     }

    /**
     * تحديث الصورة الشخصية للسائح الحالي
     */
    public function getProfileImage()
{
    $user = Auth::user();

    if (!$user || !$user->profile_image) {
        return response()->json([
            'message' => 'الصورة غير متوفرة',
            'profile_image' => null,
        ], 404);
    }

    // الصورة محفوظة في img_prof داخل الـ disk العام
    $imagePath = $user->profile_image;

    // تأكد من المسار داخل disk 'public'
    if (!Storage::disk('public')->exists($imagePath)) {
        return response()->json([
            'message' => 'الصورة غير موجودة',
            'profile_image' => $imagePath,
        ], 404);
    }

    return response()->json([
        'message' => 'تم جلب الصورة بنجاح',
        'profile_image_url' => asset('storage/' . $imagePath),
        'user'=> $user,
        'tourist'=>$user->tourist,
    ]);
}

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // حذف الصورة القديمة إذا كانت موجودة
        if ($user->profile_image) {
            $oldImagePath = 'public/img_prof/' . $user->profile_image;
            Storage::delete($oldImagePath);
        }

        // حفظ الصورة الجديدة
        $image = $request->file('profile_image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = $image->storeAs('public/img_prof', $imageName);

        // تحديث قاعدة البيانات
        $user->profile_image = $imageName;
        $user->save();

        return response()->json([
            'message' => 'تم تحديث الصورة بنجاح',
            'profile_image_url' => asset(Storage::url($imagePath))
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
        'profile_image' =>$user->profile_image,
        'profile_image_url' => $user->profile_image ? asset('storage/'.$user->profile_image) : null,

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
