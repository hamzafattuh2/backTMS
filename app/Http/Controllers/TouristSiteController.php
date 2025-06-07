<?php

namespace App\Http\Controllers;
use App\Http\Resources\TouristSiteResource;
use App\Models\TouristSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TouristSiteController extends Controller
{
    /**
     * Display popular tourist sites
     */
    public function popularSites()
    {
        $popularSites = TouristSite::where('category', 'popular')
            ->orderBy('average_rating', 'desc')
            ->get(['id', 'name', 'main_image', 'address', 'average_rating']);

        return response()->json([
            'data' => $popularSites
        ]);
    }

    /**
     * Display all sites by category
     */
    public function sitesByCategory($category)
    {
        $validCategories = ['popular', 'nature', 'outdoors', 'historic', 'landmarks'];

        if (!in_array($category, $validCategories)) {
            return response()->json([
                'message' => 'Invalid category'
            ], 400);
        }

        $sites = TouristSite::where('category', $category)
            ->orderBy('average_rating', 'desc')
            ->get(['id', 'name', 'main_image', 'address', 'average_rating']);

        return response()->json([
            'data' => $sites
        ]);
    }

    /**
 * Display full details of a specific tourist site
 */
public function getSiteDetails($id)
{
    // return Cache::remember("site_{$id}", now()->addHours(2), function() use ($id) {
        $site = TouristSite::find($id);

        if (!$site) {
            return response()->json([
                'message' => 'Tourist site not found'
            ], 404);
        }

        $site->increment('views_count');

        return response()->json([
            'data' => new TouristSiteResource($site)
        ]);
    // });
}
}
