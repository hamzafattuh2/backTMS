<?php

namespace App\Http\Middleware;

use App\Models\Trip;
use App\Models\TripPriceSuggestion;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckGuideSuggestionDates
{
    /**
     * يمنع المرشد من متابعة الطلب إذا كان هناك تداخلٍ زمني
     * بين رحلاته الحالية والرحلة المرتبطة باقتراح السعر.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /*------------------------------------------------------------------
        | 1) جلب بيانات المرشد من التوكِن
        *-----------------------------------------------------------------*/
        $guide   = $request->user();
        $guideId = $guide->id;

        /*------------------------------------------------------------------
        | 2) رقم اقتراح السعر يأتي عادةً من الـ route parameter {suggestion}
            *-----------------------------------------------------------------*/
            $suggestionId = $request->route('suggestion');   // مثال: /suggestions/{suggestion}/confirm
            $priceSuggestion = TripPriceSuggestion::find($suggestionId);

            if (! $priceSuggestion) {
                return response()->json([
                    'message' => 'سجل اقتراح السعر غير موجود.'
                ], 404);
            }

            /*------------------------------------------------------------------
            | 3) جلب الرحلة المطلوب الإشراف عليها
            *-----------------------------------------------------------------*/
            $targetTrip = Trip::find($priceSuggestion->trip_id);

            if (! $targetTrip) {
                return response()->json([
                    'message' => 'الرحلة المرتبطة بالاقتراح غير موجودة.'
                ], 404);
            }
            if($targetTrip->guide_id==$guideId ){
     return response()->json([
                    'message' => 'انت مشرف الرحلة بالفعل'
                ], 404);

            }

        /*------------------------------------------------------------------
        | 4) جميع رحلات هذا المرشد
        *-----------------------------------------------------------------*/
        $currentTrips = Trip::where('guide_id', $guideId)->get();

        /*------------------------------------------------------------------
        | 5) فحص التداخل الزمني
        *-----------------------------------------------------------------*/
        $conflict = $currentTrips->first(function (Trip $trip) use ($targetTrip) {
            return $trip->start_date <= $targetTrip->end_date &&
                   $trip->end_date   >= $targetTrip->start_date;
        });

        /*------------------------------------------------------------------
        | 6) إذا وُجد تعارض أوقف الطلب وأرجِع استجابة
        *-----------------------------------------------------------------*/
        if ($conflict) {
            return response()->json([
                'message'        => 'لا يمكنك الإشراف: هناك تداخل زمني مع رحلة أخرى.',
                'your_trip_id'   => $conflict->id,
                'your_from'      => $conflict->start_date->format('Y-m-d H:i'),
                'your_to'        => $conflict->end_date->format('Y-m-d H:i'),
                'requested_trip' => $targetTrip->id,
                'requested_from' => $targetTrip->start_date->format('Y-m-d H:i'),
                'requested_to'   => $targetTrip->end_date->format('Y-m-d H:i'),
            ], 422);
        }

        /*------------------------------------------------------------------
        | 7) لا يوجد تعارض ➜ دع الطلب يواصل طريقه
        *-----------------------------------------------------------------*/
        return $next($request);
    }
}
