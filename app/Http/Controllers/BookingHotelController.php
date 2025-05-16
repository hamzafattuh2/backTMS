<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\BookingHotel;
use Illuminate\Support\Facades\Auth;

class BookingHotelController extends Controller
{
    public function book(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'special_requests' => 'nullable|string',
        ]);
        $booking = BookingHotel::create([
            'hotel_id' => $hotel->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'booking_date' => $validated['booking_date'],
            'special_requests' => $validated['special_requests'] ?? null,
            'payment_status' => 'not pay',
        ]);
        return response()->json([
            'message' => 'Hotel booked successfully',
            'booking' => $booking
        ], 201);
    }
} 