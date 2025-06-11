<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TripResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Validation\Rule;
use App\Models\TripDay;
use App\Models\TourGuide;
use App\Models\PrivateTripRequest;
use App\Models\TripActivity;
use App\Models\TripPriceSuggestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Notifications\PriceSuggested;   // أعلى الملف
class TripController extends Controller
{
    public function publicTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'public')
            ->whereIn('status', ['active', 'public', 'confirmed'])
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function createPrivateTrip(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'language_guide' => 'required|string',
            'price' => 'required|numeric',
            'activities' => 'required|array', // array of days
            'activities.*.date' => 'required|date',
            'activities.*.day_number' => 'required|integer',
            'activities.*.items' => 'required|array', // array of activities for the day
            'activities.*.items.*.title' => 'required|string',
            'activities.*.items.*.description' => 'required|string',
            'guide_id' => 'nullable|exists:users,id',
        ]);
        $trip = Trip::create([
            'user_id' => $request->user()->id,
            'guide_id' => $validated['guide_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'language_guide' => $validated['language_guide'],
            'days_count' => (new \DateTime($validated['end_date']))->diff(new \DateTime($validated['start_date']))->days + 1,
            'price' => $validated['price'],
            'status' => 'pending',
            'public_or_private' => 'private',
            'delete_able' => true,
        ]);
        foreach ($validated['activities'] as $day) {
            $tripDay = TripDay::create([
                'trip_id' => $trip->id,
                'day_number' => $day['day_number'],
                'date' => $day['date'],
            ]);
            foreach ($day['items'] as $activity) {
                TripActivity::create([
                    'trip_day_id' => $tripDay->id,
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                ]);
            }
        }
        return response()->json(['message' => 'Private trip created', 'trip' => $trip->load('tripDays.tripActivities')]);
    }
    public function requestGuide(Request $request, $tripId)
    {
        $request->validate(['guide_id' => 'required|exists:users,id']);
        $trip = Trip::where('id', $tripId)->where('user_id', $request->user()->id)->firstOrFail();
        $trip->guide_id = $request->guide_id;
        $trip->status = 'waiting_guide';
        $trip->save();
        return response()->json(['message' => 'Guide requested', 'trip' => $trip]);
    }
    public function confirmPrice(Request $request, $tripId)
    {
        $request->validate(['suggestion_id' => 'required|exists:trip_price_suggestions,id']);
        $trip = Trip::where('id', $tripId)->where('user_id', $request->user()->id)->firstOrFail();
        $suggestion = TripPriceSuggestion::where('id', $request->suggestion_id)->where('trip_id', $trip->id)->firstOrFail();
        $suggestion->is_accepted = true;
        $suggestion->save();
        $trip->price = $suggestion->price;
        $trip->status = 'confirmed';
        $trip->save();
        return response()->json(['message' => 'Price confirmed', 'trip' => $trip]);
    }
    public function completedPublicTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'public')
            ->where('status', 'completed')
            ->whereHas('bookingChairTrips', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function completedPrivateTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'private')
            ->where('status', 'completed')
            ->where('user_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function ongoingPrivateTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'private')
            ->where('status', 'ongoing')
            ->where('user_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function deletePrivateTrip(Request $request, $tripId)
    {
        $trip = Trip::where('id', $tripId)
            ->where('public_or_private', 'private')
            ->where('user_id', $request->user()->id)
            ->where('status', 'not started')
            ->firstOrFail();
        $trip->delete();
        return response()->json(['message' => 'Private trip deleted']);
    }
    public function offerPrice(Request $request, $tripId)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $trip = Trip::findOrFail($tripId);

        // 1. Check if the trip is public
        if ($trip->public_or_private === 'public') {
            return response()->json([
                'message' => 'You cannot offer a price because this is a public trip and the price is fixed by the admin.'
            ], 403);
        }
        $guide      = $request->user()->tourGuide;
        // $user= $request->user()->id;
// $guide= $user->tourGuide;
$guideId=$guide->id;
        // 2. Check if a guide is already assigned (and it's not the current user)
        if ($trip->guide_id !== null && $trip->guide_id !== $guideId) {
            return response()->json([
                'message' => 'A tour guide has already been assigned to this trip. You cannot suggest a new price.'
            ], 403);
        }

        // 3. Check if price is already set in the trip (not null or empty)
        if (!is_null($trip->price) && $trip->price !== '') {
            return response()->json([
                'message' => 'A price has already been set for this trip. Price suggestion is not allowed.'
            ], 403);
        }

        // 4. Check if a previous suggestion was already accepted
        $existingAccepted = TripPriceSuggestion::where('trip_id', $tripId)
            ->where('is_accepted', true)
            ->first();

        if ($existingAccepted) {
            return response()->json([
                'message' => 'A price suggestion has already been accepted by the tourist.'
            ], 403);
        }

        // 5. Check if there is already a pending suggestion by this guide
        $existingPending = TripPriceSuggestion::where('trip_id', $tripId)
            ->where('guide_id', $request->user()->id)
            ->where('is_accepted', false)
            ->first();

        if ($existingPending) {
            return response()->json([
                'message' => 'You have already submitted a price suggestion. Please wait for the tourist to respond.'
            ], 403);
        }

        // Create new suggestion
        $suggestion = TripPriceSuggestion::create([
            'trip_id' => $trip->id,
            'guide_id' => $request->user()->id,
            'price' => $request->price,
            'is_accepted' => false,
        ]);

    $tripOwner = $trip->user;             // علاقة belongsTo في Trip
    $tripOwner->notify(new PriceSuggested($suggestion));
        return response()->json([
             'message'    => 'Price suggestion submitted successfully and the tourist has been notified.',
        'suggestion' => $suggestion
        ]);
    }

    public function guideCompletedPrivateTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'private')
            ->where('status', 'completed')
            ->where('guide_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function guideCompletedPublicTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'public')
            ->where('status', 'completed')
            ->where('guide_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function guideOngoingPrivateTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'private')
            ->where('status', 'ongoing')
            ->where('guide_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function guideOngoingPublicTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'public')
            ->where('status', 'ongoing')
            ->where('guide_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function privateTripsWithoutGuide(Request $request)
    {
        $trips = Trip::whereNull('guide_id')
            ->whereIn('status', ['pending', 'waiting_guide'])//قيد الانتظار او انتظار غايد
            ->where('public_or_private', 'private')//حمزة

            ->get();
        return response()->json(['trips' => $trips]);
    }
   public function indexByStatus(Request $request): JsonResponse
{
    /*----------------------------------------------------------
    | 1) قراءة مُدخلات الـ query‑string
    *---------------------------------------------------------*/
    $status        = $request->query('status');          // ?status=...
    $languageGuide = $request->query('language_guide');  // ?language_guide=...

    /*----------------------------------------------------------
    | 2) التحقق من صحة قيمة status (إن وُجدت)
    *---------------------------------------------------------*/
    $allowedStatus = ['with-guide', 'pending', 'finished'];
    if ($status !== null && ! in_array($status, $allowedStatus, true)) {
        return response()->json(['message' => 'قيمة ‎status غير مسموحة.'], 422);
    }

    /*----------------------------------------------------------
    | 3) بناء الاستعلام بشكل ديناميكي
    *---------------------------------------------------------*/
    $query = Trip::query();

    // فلترة حسب اللغة إن طُلِبت
    if ($languageGuide !== null) {
        $query->where('language_guide', $languageGuide);
    }

    // فلترة حسب الحالة إن طُلِبت
    if ($status !== null) {
        $query->where('status', $status);
    }

    /*----------------------------------------------------------
    | 4) تنفيذ الاستعلام مع ترقيم الصفحات (اختياري)
    *---------------------------------------------------------*/
    $trips = $query->get();   // يمكن استخدام ->get() فقط إن لم ترغب في pagination

    /*----------------------------------------------------------
    | 5) إرجاع النتيجة
    *---------------------------------------------------------*/
    return response()->json($trips, 200);
}
    // public function createPrivateTrip2(Request $request)
    // {
    //     $request->validate([
    //         'startDate' => 'required|date',
    //         'daysOfCount' => 'required|integer|min:1',
    //         'desribeForEachDay' => 'required|array',
    //         'desribeForEachDay.*' => 'required|string',
    //         'languageOfTrip' => 'required|string'
    //     ]);

    //     $startDate = Carbon::parse($request->startDate);
    //     $endDate = $startDate->copy()->addDays($request->daysOfCount);

    //     $trip = Trip::create([
    //         'user_id' => Auth::user()->id,
    //         'guide_id' => null,
    //         'title' => null,
    //         'description' => json_encode($request->desribeForEachDay),
    //         'start_date' => $startDate,
    //         'end_date' => $endDate,
    //         'languageOfTrip' => $request->languageOfTrip,
    //         'days_count' => $request->daysOfCount,
    //         'price' => null,
    //         'status' => 'تم حجز رحلة خاصة وينتظر رد المشرف السياحي',
    //         'public_or_private' => 'private',
    //         'delete_able' => true,
    //     ]);

    //     return response()->json([
    //         'message' => 'تم إنشاء الرحلة بنجاح',
    //         'trip' => $trip
    //     ], 201);
    // }
   public function createPrivateTrip2(Request $request)
{
    $validated = $request->validate([
        'startDate' => 'required|date|after_or_equal:today',
        'daysOfCount' => 'required|integer|min:1|max:30',
        'desribeForEachDay' => 'required|array|size:'.$request->daysOfCount,
        'desribeForEachDay.*' => 'required|string|max:500',
            // 'languageOfTrip' => [
            //     'required',
            //     'string',
            //     Rule::exists('tour_guides', 'languages')->where(function ($query) {
            //         return $query->where('confirmByAdmin', true);
            //     })
            // ]
    ]);

    DB::beginTransaction();

    try {
        // إنشاء الرحلة الأساسية
        $trip = Trip::create([
            'user_id' => Auth::id(),
            'start_date' => Carbon::parse($validated['startDate']),
            'end_date' => Carbon::parse($validated['startDate'])->addDays($validated['daysOfCount']),
            'description' => json_encode($validated['desribeForEachDay']),
            // 'languageOfTrip' => $validated['languageOfTrip'],
            'days_count' => $validated['daysOfCount'],
            'status' => 'pending_guide_approval',
            'public_or_private' => 'private',
        ]);

        // البحث عن المرشدين المتاحين
        $matchingGuides = TourGuide::where('languages', 'like', '%'.$validated['languageOfTrip'].'%')
            ->where('confirmByAdmin', true)
            ->active() // افترض أن لديك scope لعرض المرشدين النشطين فقط
            ->get(['id', 'user_id', 'languages']);

        if ($matchingGuides->isEmpty()) {
            throw new \Exception('لا يوجد مرشدين متاحين بهذه اللغة');
        }

        // إنشاء طلبات الرحلات
        $requests = $matchingGuides->map(function ($guide) use ($trip, $validated) {
            return [
                'trip_id' => $trip->id,
                'tourist_id' => Auth::id(),
                'guide_id' => 7,
                // 'title_request' => "طلب رحلة بلغة: {$validated['languageOfTrip']}",
                'created_at' => now(),
                'updated_at' => now()
            ];
        });

        // إدراج جماعي لأفضل أداء
        PrivateTripRequest::insert($requests->toArray());

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الرحلة وإرسال الطلبات',
            'data' => [
                'trip' => new TripResource($trip),
                'sent_requests_count' => $matchingGuides->count()
            ]
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('فشل إنشاء رحلة خاصة', [
            'user' => Auth::id(),
            'error' => $e->getMessage(),
            'request' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'فشل في إنشاء الرحلة: ' . $e->getMessage()
        ], 500);
    }
}
public function confirmByGuide(Request $request)
{
    $request->validate([
        'id_trip' => 'required|exists:trips,id',
        'guide_id' => 'required|exists:tour_guides,id',
        'price' => 'required|numeric|min:0'
    ]);

    $trip = Trip::find($request->id_trip);

    $trip->update([
        'guide_id' => $request->guide_id,
        'price' => $request->price,
        'status' => 'تم تأكيد الرحلة من قبل المرشد',
        'confirm_by_guide' => 1
    ]);

    return response()->json([
        'message' => 'تم تأكيد الرحلة من قبل المرشد بنجاح',
        'trip' => $trip
    ]);
}



public function showDetail($id)
{
    $trip = Trip::with('activities')->find($id);

    if (!$trip) {
        return response()->json([
            'success' => false,
            'message' => 'Trip not found'
        ], 404);
    }

    $response = [
        'id' => $trip->id,
        'name' => $trip->name,
        'city' => $trip->city,
        'overview' => $trip->overview,
        'short_overview' => $trip->short_overview,
        'main_image' => [
            'partial_path' => $trip->main_image,
            'full_url' => asset('storage/'.$trip->main_image)
        ],
        'gallery_images' => $trip->gallery_images ?
            array_map(function($image) {
                return [
                    'partial_path' => $image,
                    'full_url' => asset('storage/'.$image)
                ];
            }, json_decode($trip->gallery_images)) : [],
        'start_at' => $trip->start_at,
        'end_at' => $trip->end_at,
        'language' => $trip->language,
        'duration_days' => $trip->duration_days,
        'price_per_night' => $trip->price_per_night,
        'available_seats' => $trip->available_seats,
        'status' => $trip->status,
        'visibility' => $trip->visibility,
        'is_removable' => $trip->is_removable,
        'is_guide_confirmed' => $trip->is_guide_confirmed,
        'activities' => $trip->activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'description' => $activity->description,
                'day_number' => $activity->day_number,
                'date' => $activity->date
            ];
        })
    ];

    return response()->json([
        'success' => true,
        'data' => $response
    ]);
}

public function showAllTrips()
{
    $trips = Trip::select([
            'id',
            'name',
            'city',
            'short_overview',
            'main_image',
            'gallery_images',
            'duration_days',
            'price_per_night',
            'available_seats'
        ])
        ->where('status', 'published')
        ->get()
        ->map(function ($trip) {
            return [
                'id' => $trip->id,
                'name' => $trip->name,
                'city' => $trip->city,
                'short_overview' => $trip->short_overview,
                'main_image' => [
                    'partial_path' => $trip->main_image,
                    'full_url' => asset('storage/'.$trip->main_image)
                ],
                'gallery_images' => $trip->gallery_images ?
                    array_map(function($image) {
                        return [
                            'partial_path' => $image,
                            'full_url' => asset('storage/'.$image)
                        ];
                    }, json_decode($trip->gallery_images)) : [],
                'duration_days' => $trip->duration_days,
                'price_per_night' => $trip->price_per_night,
                'available_seats' => $trip->available_seats
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $trips
    ]);
}
}
