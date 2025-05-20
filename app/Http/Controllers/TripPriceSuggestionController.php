<?php


namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\JsonResponse;

class TripPriceSuggestionController extends Controller
{
    /**
     * عرض كل اقتراحات الأسعار لرحلة معيّنة.
     *
     * GET /api/trips/{trip}/price‑suggestions
     */
    public function index(Trip $trip): JsonResponse
    {
        // eager‑load المرشد (guide) مع بيانات المستخدم
        $suggestions = $trip->priceSuggestions()
                            ->with('guide:id,user_name,first_name,last_name,profile_image')
                            ->latest()   // الأحدث أولاً
                            ->get(['id', 'guide_id', 'price', 'is_accepted', 'created_at']);

        return response()->json([
            'trip_id'      => $trip->id,
            'suggestions'  => $suggestions,
        ]);
    }
}
