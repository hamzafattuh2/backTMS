<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\TripDay;
use App\Models\TripActivity;
use App\Models\TripPriceSuggestion;

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
            ->whereHas('bookingChairTrips', function($q) use ($request) {
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
        $trip = Trip::where('id', $tripId)
            ->where('guide_id', $request->user()->id)
            ->firstOrFail();
        $suggestion = TripPriceSuggestion::create([
            'trip_id' => $trip->id,
            'guide_id' => $request->user()->id,
            'price' => $request->price,
            'is_accepted' => false,
        ]);
        return response()->json(['message' => 'Price offered', 'suggestion' => $suggestion]);
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
    public function guideOngoingPrivateTrips(Request $request) { return response()->json(['trips' => []]); }
    public function guideOngoingPublicTrips(Request $request)
    {
        $trips = Trip::where('public_or_private', 'public')
            ->where('status', 'ongoing')
            ->where('guide_id', $request->user()->id)
            ->get();
        return response()->json(['trips' => $trips]);
    }
    public function tripsWithoutGuide(Request $request)
    {
        $trips = Trip::whereNull('guide_id')
            ->whereIn('status', ['pending', 'waiting_guide'])
            ->get();
        return response()->json(['trips' => $trips]);
    }
} 