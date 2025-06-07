<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripPriceSuggestion;
use App\Models\TourGuide;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;

class GuideController extends Controller
{


    /**
     * تحقّق من إمكانية إشراف المرشد الحالي على رحلة مرتبطة باقتراح سعر معيّن.
     *
     * @param  Request  $request        الطلب (يحمل توكن المصادقة)
     * @param  int      $suggestion     رقم اقتراح السعر (trip_price_suggestions.id)
     * @return JsonResponse
     */
    public function checkSuggestionDates(Request $request, int $suggestion): JsonResponse
    {
        /*------------------------------------------------------------------
        | 1) جلب بيانات المرشد من التوكِن
        *-----------------------------------------------------------------*/
        $guide = $request->user();    // كائن User المصادَق
        $guideId = $guide->id;          // معرّف المرشد

        /*------------------------------------------------------------------
        | 2) جلب سجلّ اقتراح السعر
        *-----------------------------------------------------------------*/
        $priceSuggestion = TripPriceSuggestion::find($suggestion);

        if (!$priceSuggestion) {
            return response()->json([
                'message' => 'سجل اقتراح السعر غير موجود.'
            ], 404);
        }

        /*------------------------------------------------------------------
        | 3) جلب الرحلة المطلوب الإشراف عليها (المذكورة في اقتراح السعر)
        *-----------------------------------------------------------------*/
        $targetTrip = Trip::find($priceSuggestion->trip_id);

        if (!$targetTrip) {
            return response()->json([
                'message' => 'الرحلة المرتبطة بالاقتراح غير موجودة.'
            ], 404);
        }

        /*------------------------------------------------------------------
        | 4) كل الرحلات التي يشرف عليها هذا المرشد حالياً
        *-----------------------------------------------------------------*/
        $currentTrips = Trip::where('guide_id', $guideId)->get();

        /*------------------------------------------------------------------
        | 5) البحث عن أول رحلة تتداخل تواريخها مع الرحلة المطلوبة
        *    (start <= otherEnd) && (end >= otherStart)
        *-----------------------------------------------------------------*/
        $conflict = $currentTrips->first(function (Trip $trip) use ($targetTrip) {
            return $trip->start_date <= $targetTrip->end_date &&
                $trip->end_date >= $targetTrip->start_date;
        });

        /*------------------------------------------------------------------
        | 6) إرجاع نتيجة التحقق
        *-----------------------------------------------------------------*/
        if ($conflict) {
            // يوجد تداخل زمني – رفض
            return response()->json([
                'message' => 'لا يمكنك الإشراف: هناك تداخل زمني مع رحلة أخرى.',
                'your_trip_id' => $conflict->id,
                'your_from' => $conflict->start_date->format('Y-m-d H:i'),
                'your_to' => $conflict->end_date->format('Y-m-d H:i'),
                'requested_trip' => $targetTrip->id,
                'requested_from' => $targetTrip->start_date->format('Y-m-d H:i'),
                'requested_to' => $targetTrip->end_date->format('Y-m-d H:i'),
            ], 422);
        }

        // لا يوجد تعارض – قبول
        return response()->json([
            'message' => 'يمكنك الإشراف على هذه الرحلة.',
            'trip_id' => $targetTrip,
        ]);
    }
  public function show(Trip $trip): JsonResponse   // لاحظ type‑hint
    {
        // جلب اقتراحات السعر المرتبطة بالرحلة
        $suggestions = $trip->priceSuggestions;      // استخدم العلاقة أو:
        // $suggestions = TripPriceSuggestion::where('trip_id', $trip->id)->get();

        return response()->json($suggestions);
    }

     public function confirm(Request $request): JsonResponse
    {
        /*-------------------------------------------------
        | 1) المرشد المُصادَق
        *------------------------------------------------*/
        $user  = $request->user();          // كائن User
        $guide = $user->tourGuide;          // علاقة TourGuide

        /*-------------------------------------------------
        | 2) جلب السِّجل المقصود من route parameter {suggestion}
        *------------------------------------------------*/
        $suggestionId   = $request->route('suggestion');
        $priceSuggestion = TripPriceSuggestion::findOrFail($suggestionId);

        /*-------------------------------------------------
        | 3) الرحلة المُرتبطة بالاقتراح
        *------------------------------------------------*/
        $targetTrip = Trip::findOrFail($priceSuggestion->trip_id);

        /*-------------------------------------------------
        | 4) تحديث معلومات الرحلة
        *------------------------------------------------*/
        $targetTrip->guide_id = $user->id;                   // عيِّن المرشد
        $targetTrip->price    = $priceSuggestion->price;     // السعر المقترَح
        $targetTrip->status   = 'with-guide';                // أي حالة تناسب منطقك
        $targetTrip->save();

        /*-------------------------------------------------
        | 5) يمكن أيضاً وضع علامة قبول على الاقتراح
        *------------------------------------------------*/
        $priceSuggestion->is_accepted = true;
        $priceSuggestion->save();

        /*-------------------------------------------------
        | 6) استجابة JSON نهائية
        *------------------------------------------------*/
        return response()->json([
            'message'          => 'تم تأكيد المرشد لهذه الرحلة بنجاح.',
            'trip'             => $targetTrip->only([
                                    'id', 'title', 'start_date', 'end_date',
                                    'price', 'status', 'guide_id'
                                 ]),
            'accepted_suggest' => $priceSuggestion->only([
                                    'id', 'price', 'is_accepted'
                                 ]),
        ]);
    }
     public function index(Request $request)
    {
        $guides = TourGuide::with('user')->where('confirmByAdmin', true)->get();
        return response()->json(['guides' => $guides]);
    }
    public function registerGuide(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
            'type' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'languages' => 'required|string',
            'years_of_experience' => 'required|integer',
            'license_picture_path' => 'nullable|file|mimes:jpeg,png,pdf',
            'cv_path' => 'nullable|file|mimes:pdf',
        ]);
        $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public/profile_image');
            $profilePicturePath = str_replace('public/', '', $path);
        }
        $user = User::create([
            'user_name' => $validated['user_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'guide',
            'phone_number' => $validated['phone_number'],
            'profile_image' => $profilePicturePath ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
        ]);
        $licensePath = null;
        if ($request->hasFile('license_picture_path')) {
            $licensePath = $request->file('license_picture_path')->store('public/license');
            $licensePath = str_replace('public/', '', $licensePath);
        }
        $cvPath = null;
        if ($request->hasFile('cv_path')) {
            $cvPath = $request->file('cv_path')->store('public/cv');
            $cvPath = str_replace('public/', '', $cvPath);
        }
        $guide = TourGuide::create([
            'user_id' => $user->id,
            'languages' => $validated['languages'],
            'years_of_experience' => $validated['years_of_experience'],
            'license_picture_path' => $licensePath,
            'cv_path' => $cvPath,
            // 'guide_picture_path' => $profilePicturePath,
        ]);
        $token = $user->createToken('guide_auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Guide registered successfully',
            'user' => $user,
            'guide' => $guide,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }
    public function loginGuide(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->type != 'guide') {
            return response()->json(['message' => 'You are not a guide'], 401);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
        $token = $user->createToken('guide_auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
    // public function logoutGuide(Request $request)
    // {
    //     $user = $request->user();
    //     $user->currentAccessToken()->delete();
    //     return response()->json(['message' => 'Logout successful']);
    // }
}
