<?php
namespace App\Http\Middleware;
use App\Models\TripPriceSuggestion;
use App\Models\Trip;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuideLanguageMatchesTrip
{
    public function handle(Request $request, Closure $next): Response
    {
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
            $trip = Trip::find($priceSuggestion->trip_id);

            if (! $trip) {
                return response()->json([
                    'message' => 'الرحلة المرتبطة بالاقتراح غير موجودة.'
                ], 404);
            }


        $user = $request->user();            // المُستخدم من التوكن
        // $trip = $request->route('trip');     // كائن Trip بفضل Route Model Binding

        // تأكُّد أن المستخدم مرشد سياحي مُفعَّل
        if ($user->type !== 'guide' || !$user->tourGuide) {
            return response()->json([
                'message' => 'Only guides can perform this action.'
            ], 403);
        }

        // حوّل لُغات المرشد إلى مصفوفة مُنظَّفة
        $guideLanguages = collect(explode(',', $user->tourGuide->languages))
                          ->map(fn ($lang) => trim(strtolower($lang)));

        // طابق لغة الرحلة
        if (!$guideLanguages->contains(strtolower($trip->language_guide))) {
            return response()->json([
                'message'  => "Guide language mismatch. Trip requires {$trip->language_guide}.",
                'your_languages' => $guideLanguages->implode(', ')
            ], 403);
        }

        return $next($request); // ✅ المطابقة نجحت
    }
}
