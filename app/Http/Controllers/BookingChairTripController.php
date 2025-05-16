<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingChairTripController extends Controller
{
    public function bookChair(Request $request, $trip)
    {
        return response()->json(['message' => 'Chair booked in public trip']);
    }
} 