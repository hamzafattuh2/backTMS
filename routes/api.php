<?php
use App\Http\Controllers\PrivateTripController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TourGuideController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingHotelController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripPriceSuggestionController;
use App\Http\Controllers\TouristSiteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PublicTripController;


use App\Http\Controllers\CardController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//test1
// Route::get('/dashboard', function () {
//     return "view('dashboard')";
// })->middleware([
//     auth::class,
// // verified::class,
// TwoFactor::class
// ])->middleware('auth:sanctum','TwoFactor')->name('dashboard');//add two factor


Route::resource('verify', TwoFactorController::class);
Route::post('/verify', [TwoFactorController::class, 'store'])
    ->middleware('auth:sanctum');
//end test1

Route::get('/user/home', [UserController::class, 'get1'])->middleware('auth:sanctum');



Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::post('registerTourGuide', [TourGuideController::class, 'registerTourGuide']);//true
// Route::post('loginTourGuide', [TourGuideController::class, 'loginTourGuide']);//for delete

Route::post('registerTourist', [TouristController::class, 'registerTourist']);
// Route::post('loginTourist', [TouristController::class, 'loginTourist']);//for delte

Route::prefix('wallets')->group(function () {
    Route::post('/deposit', [WalletController::class, 'deposit'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum', 'is_guide'])->group(function () {//الميدل وير التاني تبع الايز غايد مو شغال ما يقرءه
    // Route::post('guide/logout', [GuideController::class, 'logoutGuide']); // شغال بس منستخدم لوغ اوت العادية تبع اليوزر كونترولر
    Route::post('trips/{trip}/offer-price', [TripController::class, 'offerPrice']);

    Route::get('guide/trips/private/completed', [TripController::class, 'guideCompletedPrivateTrips']);//شغال مية مية
    Route::get('guide/trips/public/completed', [TripController::class, 'guideCompletedPublicTrips']);//شغال مية مية

    Route::get('guide/trips/private/ongoing', [TripController::class, 'guideOngoingPrivateTrips']);//شغال مية مية
    Route::get('guide/trips/public/ongoing', [TripController::class, 'guideOngoingPublicTrips']);//شغال مية مية

    Route::get('private-trips/without-guide', [TripController::class, 'privateTripsWithoutGuide']); //شغال مية مية
    Route::get('private-trips/without-guide', [TripController::class, 'privateTripsWithoutGuide']); //شغال مية مية

    Route::get('/check-guide', [TourGuideController::class, 'checkGuideConfirmation']);

});


Route::middleware(['auth:sanctum', 'is_tourist'])->group(function () {
    Route::get('tourist/profile', [TouristController::class, 'getProfile']); // جلب البيانات
    Route::post('tourist/profile/update', [TouristController::class, 'updateProfile']);

});//شغال مية مية بس لازم يكون الباسورد فيه سترينغ انا حطيته بس بدون ما يتاكد من الباسورد

// });
// routes/api.php
Route::middleware(['auth:sanctum', 'is_guide'])     // أو أيّ حارس تراه مناسباً
    ->get('/trips/{trip}/price‑suggestions', [TripPriceSuggestionController::class, 'index']);
// routes/api.php
Route::middleware(['auth:sanctum', 'is_guide', 'language_match', 'check_suggestion_dates'])
    ->get('trips/confirm/{suggestion}', [GuideController::class, 'confirm'])->whereNumber('suggestion');

Route::middleware(['auth:sanctum', 'is_guide'])
    ->get('trips/{trip}', [GuideController::class, 'show'])->whereNumber('trip');

// Route::middleware(['auth:sanctum', 'is_guide', 'language_match'])->group(function () {
// Route::get('trips/{trip}/confirm', [GuideController::class, 'confirm']) ->whereNumber('trip');

// });

Route::middleware(['auth:sanctum', 'is_guide'])
    ->get(
        'trips/check-suggestion/{suggestion}',
        [GuideController::class, 'checkSuggestionDates']
    )
    ->whereNumber(['suggestion']);

Route::get('trips1', [TripController::class, 'indexByStatus']);

Route::middleware(['auth:sanctum'])   // مثال لسياسة صلاحيات
    ->post('guides/notify', [TourGuideController::class, 'notifyAllGuides']);


    //بشرى and nuha
Route::post('/expert-system', [\App\Http\Controllers\ExpertSystemController::class, 'process']);
Route::post('login', [TourGuideController::class, 'login']);//lat

Route::middleware('auth:sanctum')->group(function () {
Route::get('/popular-sites', [TouristSiteController::class, 'popularSites']);
Route::get('/sites/category/{category}', [TouristSiteController::class, 'sitesByCategory']);
Route::get('/tourist-sites/{id}', [TouristSiteController::class, 'getSiteDetails']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});

// في ملف routes/api.php
Route::middleware('auth:sanctum')->post('/trips/create-private', [TripController::class, 'createPrivateTrip2']);
Route::middleware('auth:sanctum')->post('/trips/confirm-by-guide', [TripController::class, 'confirmByGuide']);

// Hotel APIs
Route::prefix('hotels')->group(function () {
    Route::get('/', [HotelController::class, 'index']);
    Route::get('/filter', [HotelController::class, 'filterByCity']);
    Route::get('/{id}', [HotelController::class, 'show']);
    Route::middleware('auth:sanctum')->post('/{id}/book', [HotelController::class, 'book']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile/image', [TouristController::class, 'getProfileImage']);
});

Route::middleware('auth:sanctum')->group(function () {
Route::post('/tourist-sites/search', [TouristSiteController::class, 'search']);
});
Route::middleware('auth:sanctum')->group(function () {

Route::post('/hotels/search', [HotelController::class, 'searchByName']);
});

Route::middleware(['auth:sanctum','is_guide'])->group(function () {
    // ملف المرشد السياحي
    Route::get('tour-guide/profile', [TourGuideController::class, 'getProfile']);//
    Route::post('tour-guide/profile/update', [TourGuideController::class, 'updateProfile']);
    Route::post('tour-guide/profile/update-image', [TourGuideController::class, 'updateProfileImage']);

});
// Route::middleware(['auth:sanctum','is_guide'])->group(function () {

Route::get('tourist-sites/most-viewed', [TouristSiteController::class, 'mostViewedSites']);

Route::get('/tourist-sites/top-viewed', [TouristSiteController::class, 'topViewedSites']);
Route::get('/trips', [TripController::class, 'showAllTrips']);
Route::get('/trip-detail/{id}', [TripController::class, 'showDetail']);
// });


Route::post('/cards', [CardController::class, 'store']);
Route::post('/cards/verify', [CardController::class, 'verifyCard']);


Route::post('/private/trip/request-create',[PrivateTripController::class,'sendPrivateTripReq'])->middleware('auth:sanctum');
Route::post('/private/trip-guid/create-offer',[PrivateTripController::class,'submitOfferForRequest'])->middleware('auth:sanctum');
Route::post('/private/trip-guid/delete-request',[PrivateTripController::class,'deletePrivateTripRequest'])->middleware('auth:sanctum');
Route::get('/private/trip-guid/requests',[PrivateTripController::class,'getMyTripRequests'])->middleware('auth:sanctum');
Route::get('/private/trip/my-offers',[PrivateTripController::class,'getMyPrivateOffers'])->middleware('auth:sanctum');
Route::post('/wallets/withdraw', [WalletController::class, 'withdraw']);

Route::get('public-trips/{id}', [PublicTripController::class, 'getTripById']);
// Route::get('public-trips', PublicTripController::class)->only(['index', 'show']);
   Route::get('/public-trips', [PublicTripController::class, 'getPublicTrips']);
    // Route::get('/{id}', [PublicTripController::class, 'getPublicTrip']);
