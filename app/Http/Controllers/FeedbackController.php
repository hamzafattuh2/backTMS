<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function tripReviews(Request $request, $tripId)
    {
        $reviews = Feedback::where('trip_id', $tripId)->get();
        return response()->json(['reviews' => $reviews]);
    }
} 