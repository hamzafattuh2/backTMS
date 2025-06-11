<?php

namespace App\Http\Controllers;
use App\Http\Resources\TouristSiteResource;
use App\Models\TouristSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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


public function search(Request $request)
{
    // التحقق من صحة المدخلات
    $validator = Validator::make($request->all(), [
        'query' => 'sometimes|string|min:2',
        'category' => 'nullable|in:popular,nature,outdoors,historic,landmarks',
        'min_rating' => 'nullable|numeric|min:0|max:5',
        'sort_order' => 'nullable|in:asc,desc'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);
    }

    // البدء ببناء الاستعلام
    $query = TouristSite::query();

    // البحث حسب الاسم فقط
    if ($request->has('query') && !empty($request->query)) {
        $searchTerm = $request->input('query');
        $query->where('name', 'LIKE', "%{$searchTerm}%");
    }

    // التصفية حسب التصنيف
    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    // التصفية حسب الحد الأدنى للتقييم
    if ($request->has('min_rating')) {
        $query->where('average_rating', '>=', $request->min_rating);
    }

    // الترتيب دائمًا حسب عدد المشاهدات
    $sortOrder = $request->input('sort_order', 'desc');
    $query->orderBy('views_count', $sortOrder);

    // جلب كل النتائج بدون حد
    $results = $query->get();

    // إذا لم توجد نتائج
    if ($results->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No tourist sites found matching your criteria',
            'suggestions' => [
                'Try a different search term',
                'Remove some filters',
                'Check the spelling'
            ]
        ], 404);
    }

    // تنسيق النتائج
    $formattedResults = $results->map(function ($site) {
        return [
            'id' => $site->id,
            'name' => $site->name,
            'main_image' => $this->getImageUrl($site->main_image),
            'gallery_images' => $this->getGalleryUrls($site->gallery_images),
            'address' => $site->address,
            'description' => $site->description,
            'category' => $site->category,
            'views_count' => $site->views_count,
            'average_rating' => $site->average_rating,
            'created_at' => $site->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $site->updated_at->format('Y-m-d H:i:s')
        ];
    });

    \Log::debug('Search Query:', [
        'SQL' => $query->toSql(),
        'Bindings' => $query->getBindings(),
        'Results' => $results->toArray()
    ]);

    return response()->json([
        'success' => true,
        'data' => $formattedResults,
        'meta' => [
            'total_results' => $results->count(),
            // 'filters_applied' => $request->only(['query', 'category', 'min_rating']),
              'filters_applied' => $request->only('query'),
            // 'sorting' => [
            //     'by' => 'views_count',
            //     'order' => $sortOrder
            // ]
        ]
    ]);
}


    /**
     * الحصول على رابط الصورة الرئيسية
     */
    private function getImageUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    /**
     * الحصول على روابط معرض الصور
     */
  private function getGalleryUrls($gallery)
{
    if (empty($gallery) || !is_array($gallery)) {
        return [];
    }

    return array_map(function($image) {
        return $this->getImageUrl($image);
    }, $gallery);
}

public function mostViewedSites()
{
    $mostViewedSites = TouristSite::orderBy('views_count')
        ->take(5)
        ->get(['id', 'name', 'main_image', 'address', 'average_rating', 'views_count']);

    return response()->json([
        'data' => $mostViewedSites
    ]);}

    public function topViewedSites()
{
    $minViewsThreshold = 5; // الحد الأدنى للمشاهدات

    $topSites = TouristSite::where('views_count', '>', $minViewsThreshold)
        ->orderBy('views_count', 'desc')
        ->take(5)
        ->get([
            'id',
            'name',
            'main_image',
            'address',
            'average_rating',
            'views_count',
            'category'
        ]);

    return response()->json([
        'count' => $topSites->count(),
        'data' => $topSites,
        'message' => $topSites->isEmpty()
            ? 'No sites meet the minimum views requirement'
            : 'Top 5 most viewed tourist sites'
    ]);
}
}
