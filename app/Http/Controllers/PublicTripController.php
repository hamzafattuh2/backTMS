<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\TourGuide;
use Illuminate\Http\Request;

use App\Models\User;


class PublicTripController extends Controller
{
    /**
     * الحصول على معلومات الرحلات العامة
     */
    // public function getPublicTrips(Request $request)
    // {
    //     // استرجاع الرحلات مع فلتر حسب الحالة إذا تم تحديدها
    //     $query = PublicTrip::query();

    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->has('city')) {
    //         $query->where('city', 'like', '%'.$request->city.'%');
    //     }

    //     $trips = $query->select([
    //         'id',
    //         'name',
    //         'short_overview',
    //         'available_seats',
    //         'price_per_person',
    //         'date_of_tour',
    //         'images',
    //         'status',
    //         'city'
    //     ])->get();

    //     // تحويل الصور من JSON إلى array
    //     $trips->transform(function ($trip) {
    //         $trip->images = json_decode($trip->images, true);
    //         $trip->main_image = $trip->images['main'] ?? null;
    //         return $trip;
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'data' => $trips
    //     ]);
    // }
    public function getPublicTrips(Request $request)
{
    $query = PublicTrip::query();

    // ... الفلاتر كما هي ...

    $trips = $query->select([
        'id',
        'name',
        'short_overview',
        'available_seats',
        'price_per_person',
        'date_of_tour',
        'images',
        'status',
        'city'
    ])->get();

    // إزالة التحويل واستخدام الصور مباشرة كمصفوفة
    $trips->transform(function ($trip) {
        // لا حاجة ل json_decode لأن images مصفوفة بالفعل
        $trip->main_image = $trip->images[0] ?? null; // أول صورة كصورة رئيسية
        return $trip;
    });

    return response()->json([
        'data' => $trips
    ]);
}

    /**
     * الحصول على رحلة محددة
     */
    // public function getPublicTrip($id)
    // {
    //     $trip = PublicTrip::select([
    //         'id',
    //         'name',
    //         'short_overview',
    //         'overview',
    //         'available_seats',
    //         'price_per_person',
    //         'date_of_tour',
    //         'meeting_point',
    //         'language',
    //         'images',
    //         'status',
    //         'city',
    //         'is_guide_confirmed'
    //     ])->findOrFail($id);

    //     $trip->images = json_decode($trip->images, true);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $trip
    //     ]);
    // }
 public function getTripById(Request $request, $id)
{
    // البحث عن الرحلة بالمعرف المطلوب
    $trip = PublicTrip::find($id);

    // إذا لم يتم العثور على الرحلة
    if (!$trip) {
        return response()->json([
            'message' => 'الرحلة غير موجودة'
        ], 404);
    }

    // لا حاجة لتحويل JSON لأن Larabil يقوم بذلك تلقائيًا
    // فقط تأكد أن images هي مصفوفة
    if (is_string($trip->images)) {
        $trip->images = json_decode($trip->images, true) ?? [];
    } elseif (!is_array($trip->images)) {
        $trip->images = [];
    }
$guide_id = $trip->guide_id;
   $guide = TourGuide::findOrFail($guide_id);

$user_id= $guide->user_id;
$user= User::findOrFail($user_id);

    // إرجاع بيانات الرحلة كاملة
    return response()->json([
        'data' => $trip

    ,'user'=>$user
,'guide'=>$guide ]);
}
}
