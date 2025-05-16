<?php
use App\Http\Controllers\TouristController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TourGuideController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\Authenticate ;
use App\Http\Middleware\TwoFactor;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingHotelController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\TripController;


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

Route::get('/user/home',[UserController::class,'get1'])->middleware('auth:sanctum');

// Route::post('register',[UserController::class,'register']);
// Route::post('login',[UserController::class,'login']);
// Route::get('login1',[UserController::class,'login1']);

Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');

Route::post('registerTourGuide',[TourGuideController::class,'registerTourGuide']);//true
Route::post('loginTourGuide',[TourGuideController::class,'loginTourGuide']);//true
// Route::post('logoutTourGuide',[TourGuideController::class,'logoutTourGuide'])->middleware('auth:sanctum');//true

Route::post('registerTourist',[TouristController::class,'registerTourist']);
Route::post('loginTourist',[TouristController::class,'loginTourist']);//true
// Route::post('logoutTourist',[TouristController::class,'logoutTourist']);//true

// Route::post('/wallets/{wallet}/deposit', [WalletController::class, 'deposit'])
//     ->middleware('auth:sanctum');

Route::prefix('wallets')->group(function () {
    Route::get('/{user}', [WalletController::class, 'show']);
    Route::post('/{user}/deposit', [WalletController::class, 'deposit']);
    Route::post('/{user}/withdraw', [WalletController::class, 'withdraw']);
    Route::post('/{user}/transfer', [WalletController::class, 'transfer']);
});

Route::middleware(['auth:sanctum', 'is_tourist'])->group(function () {
    Route::post('/hotels/{hotel}/book', [BookingHotelController::class, 'book']);
});

Route::middleware(['auth:sanctum', 'is_guide'])->group(function () {
    Route::post('guide/logout', [GuideController::class, 'logoutGuide']);
    Route::post('trips/{trip}/offer-price', [TripController::class, 'offerPrice']);
    Route::get('guide/trips/private/completed', [TripController::class, 'guideCompletedPrivateTrips']);
    Route::get('guide/trips/public/completed', [TripController::class, 'guideCompletedPublicTrips']);
    Route::get('guide/trips/private/ongoing', [TripController::class, 'guideOngoingPrivateTrips']);
    Route::get('guide/trips/public/ongoing', [TripController::class, 'guideOngoingPublicTrips']);
    Route::get('trips/without-guide', [TripController::class, 'tripsWithoutGuide']);
});
