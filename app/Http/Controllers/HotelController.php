<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    // API 1: Get all hotels with basic information
    public function index()
    {
        $hotels = Hotel::select('id', 'name', 'city', 'rating', 'number_of_reviews', 'price_per_night')
            ->where('is_active', true)
            ->get()
            ->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->city,
                    'reviews' => [
                        'rating' => $hotel->rating,
                        'number_of_reviews' => $hotel->number_of_reviews
                    ],
                    'price_per_night' => $hotel->price_per_night
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ]);
    }

    // API 2: Filter hotels by city
    public function filterByCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $hotels = Hotel::select('id', 'name', 'city', 'rating', 'number_of_reviews', 'price_per_night')
            ->where('city', 'like', '%' . $request->city . '%')
            ->where('is_active', true)
            ->get()
            ->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->city,
                    'reviews' => [
                        'rating' => $hotel->rating,
                        'number_of_reviews' => $hotel->number_of_reviews
                    ],
                    'price_per_night' => $hotel->price_per_night
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $hotels
        ]);
    }

    // API 3: Get detailed hotel information
    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $hotel->name,
                'stars' => $hotel->stars,
                'price_per_night' => $hotel->price_per_night,
                'description' => $hotel->description,
                'images' => $hotel->images,
                'rating' => $hotel->rating,
                'number_of_reviews' => $hotel->number_of_reviews,
                'amenities' => $hotel->amenities,
                'contact_email' => $hotel->contact_email,
                'contact_phone' => $hotel->contact_phone,
                'guide_name' => $hotel->guide_name,
                'available_seats' => $hotel->available_seats
            ]
        ]);
    }

    // API 4: Book a hotel room
    public function book(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'number_of_days' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $hotel = Hotel::findOrFail($id);

        // Check if hotel is active
        if (!$hotel->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel is not available for booking'
            ], 400);
        }

        // Check if there are available seats
        if ($hotel->available_seats !== null && $hotel->available_seats < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'No available seats'
            ], 400);
        }

        // Calculate total price
        $total_price = $hotel->price_per_night * $request->number_of_days;

        // Create booking
        $booking = $hotel->bookingHotels()->create([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'number_of_days' => $request->number_of_days,
            'total_price' => $total_price,
            'status' => 'pending'
        ]);

        // Update available seats if applicable
        if ($hotel->available_seats !== null) {
            $hotel->decrement('available_seats');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel room booked successfully',
            'data' => [
                'booking_id' => $booking->id,
                'hotel_name' => $hotel->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'number_of_days' => $request->number_of_days,
                'total_price' => $total_price
            ]
        ]);
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'stars' => 'required|integer|between:1,5',
            'amenities' => 'nullable',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Ensure amenities is JSON encoded if needed
        $validated['amenities'] = json_encode($request->amenities ?? []);

        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'stars' => 'required|integer|between:1,5',
            'amenities' => 'nullable',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['amenities'] = json_encode($request->amenities ?? []);

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully');
    }
}
