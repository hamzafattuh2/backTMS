<?php

namespace App\Http\Controllers;

use App\Models\TourGuide;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateTripRequest;
use Illuminate\Support\Facades\DB;
class PrivateTripController extends Controller
{

public function sendPrivateTripReq(Request $request)
{
    $data = $request->all();
    $langString = $request->input('lang');
    $langArray = array_map('trim', explode(',', $langString));
    $startDate = Carbon::parse($request->input('start_date'));
    $daysCount = (int) $request->input('days_count');
    $endDate = $startDate->copy()->addDays($daysCount - 1);
    $guides = TourGuide::all();
    $availableGuideIds = [];
    foreach ($guides as $guide) {
        $hasConflict = $guide->trips()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
            })
            ->exists();
        if ($hasConflict) {
            continue;
        }
        $guideLangArray = array_map('trim', explode(',', $guide->languages));
        $hasMatchingLang = count(array_intersect($langArray, $guideLangArray)) > 0;
        if (!$hasMatchingLang) {
            continue;
        }
        $availableGuideIds[] = $guide->id;
        PrivateTripRequest::create([
            'user_id' => auth()->id(),
            'tour_id' => $guide->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'lang' => implode(', ', $langArray),
            'days' => $data['days'],
            'count_days' => $daysCount,
            'status' => 'pend',
        ]);
    }
    return response()->json([
        'message' => 'Private trip requests sent successfully.'
    ]);
}

public function getMyTripRequests(Request $request)
{
    $authUser = auth()->user();

    // Get the guide record for this user
    $guide = DB::table('tour_guides')->where('user_id', $authUser->id)->first();

    if (!$guide) {
        return response()->json([
            'message' => 'This user is not a registered tour guide.'
        ], 404);
    }

    // Join private_trip_requests with users table
    $requests = DB::table('private_trip_requests')
        ->join('users', 'private_trip_requests.user_id', '=', 'users.id')
        ->where('private_trip_requests.tour_id', $guide->id)
        ->where('private_trip_requests.status','pend')
        ->select(
            'private_trip_requests.*',
            'users.first_name as user_name',
        )
        ->get();

    return response()->json([
        'requests' => $requests,
    ]);
}

public function submitOfferForRequest(Request $request)
{
    $request->validate([
        'trip_request_id' => 'required|exists:private_trip_requests,id',
        'price' => 'required',
    ]);

    $authUser = auth()->user();

    // Fetch guide based on the logged-in user
    $guide = DB::table('tour_guides')->where('user_id', $authUser->id)->first();

    if (!$guide) {
        return response()->json(['message' => 'You are not a registered tour guide.'], 403);
    }

    // Make sure the request belongs to this guide
    $tripRequest = DB::table('private_trip_requests')
        ->where('id', $request->trip_request_id)
        ->where('tour_id', $guide->id)
        ->first();

    if (!$tripRequest) {
        return response()->json(['message' => 'This trip request does not belong to you.'], 404);
    }

    // Update status of the trip request to 'wait'
    DB::table('private_trip_requests')
        ->where('id', $request->trip_request_id)
        ->update(['status' => 'wait', 'updated_at' => now()]);

    // Create a private offer for the user
    DB::table('private_offers')->insert([
        'user_id' => $tripRequest->user_id,
        'trip_request_id' => $tripRequest->id,
        'price' => $request->price,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'message' => 'Offer submitted successfully.'
    ]);
}

public function deletePrivateTripRequest(Request $request)
{
    $authUser = auth()->user();
    $data = $request->all();

    // Find the trip request by ID
    $tripRequest = DB::table('private_trip_requests')
        ->where('id', $data['id'])
        ->where('user_id', $authUser->id)
        ->first();

    // If not found or not owned by the user
    if (!$tripRequest) {
        return response()->json([
            'message' => 'Private trip request not found or not authorized to delete.'
        ], 404);
    }

    // Delete the request
    DB::table('private_trip_requests')->where('id', $data['id'])->delete();

    return response()->json([
        'message' => 'Private trip request deleted successfully.'
    ]);
}

public function getMyPrivateOffers()
{
    $authUser = auth()->user();

    // Join private_offers with related tables
    $offers = DB::table('private_offers')
        ->join('private_trip_requests', 'private_offers.trip_request_id', '=', 'private_trip_requests.id')
        ->join('tour_guides', 'private_trip_requests.tour_id', '=', 'tour_guides.id')
        ->join('users', 'tour_guides.user_id', '=', 'users.id') // to get guide's user info
        ->where('private_offers.user_id', $authUser->id)
        ->select(
            'private_offers.id as offer_id',
            'private_offers.price',
            'private_offers.created_at as offer_created_at',

            'private_trip_requests.id as request_id',
            'private_trip_requests.start_date',
            'private_trip_requests.end_date',
            'private_trip_requests.lang',
            'private_trip_requests.status',

            'tour_guides.id as guide_id',
            'tour_guides.languages as guide_languages',
            'tour_guides.years_of_experience',

            'users.first_name as guide_first_name',
            'users.last_name as guide_last_name',
        )
        ->get();

    return response()->json([
        'offers' => $offers,
    ]);
}

public function acceptPrivateOffer(Request $request)
{
    $authUser = auth()->user();
    $offerId = $request->input('offer_id');

    // 1. Get the selected offer
    $offer = DB::table('private_offers')
        ->where('id', $offerId)
        ->first();

    if (!$offer) {
        return response()->json(['message' => 'Offer not found.'], 404);
    }

    // 2. Get the associated trip request
    $tripRequest = DB::table('private_trip_requests')
        ->where('id', $offer->trip_request_id)
        ->first();

    if (!$tripRequest || $tripRequest->user_id != $authUser->id) {
        return response()->json(['message' => 'Unauthorized or request not found.'], 403);
    }

    // 3. Delete all offers for this request
    DB::table('private_offers')
        ->where('trip_request_id', $tripRequest->id)
        ->delete();

    // 4. Delete all trip requests for the same guide in overlapping date range
    DB::table('private_trip_requests')
        ->where('tour_id', $tripRequest->tour_id)
        ->where(function ($query) use ($tripRequest) {
            $query->whereBetween('start_date', [$tripRequest->start_date, $tripRequest->end_date])
                  ->orWhereBetween('end_date', [$tripRequest->start_date, $tripRequest->end_date]);
        })
        ->delete();

    // ✅ 5. Delete all other requests from this user
    DB::table('private_trip_requests')
        ->where('user_id', $authUser->id)
        ->where('id', '!=', $tripRequest->id) // except the accepted one (we already deleted it above anyway)
        ->delete();

    // 6. Create the confirmed trip
    DB::table('trips')->insert([
        'user_id' => $tripRequest->user_id,
        'guide_id' => $tripRequest->tour_id,
        'title' => 'Private Trip',
        'description' => 'Trip confirmed based on user request.',
        'start_date' => Carbon::parse($tripRequest->start_date)->startOfDay(),
        'end_date' => Carbon::parse($tripRequest->end_date)->endOfDay(),
        'languageOfTrip' => $tripRequest->lang,
        'days_count' => $tripRequest->count_days,
        'price' => $offer->price,
        'status' => 'confirmed',
        'public_or_private' => 'private',
        'delete_able' => true,
        'confirm_by_Guide' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json([
        'message' => 'Offer accepted, trip created, and all other requests removed.'
    ]);
}

}
