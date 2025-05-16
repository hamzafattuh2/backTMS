<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\HotelController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', function () {
    return view('admin.login'); // You need to create this Blade view
})->name('admin.login');

Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/hotels/create',  [HotelController::class, 'create'])->name('admin.hotels.create');
    Route::post('/admin/hotels/store',  [HotelController::class, 'store'])->name('admin.hotels.store');
    Route::get('/admin/hotels/edit/{hotel}',  [HotelController::class, 'edit'])->name('admin.hotels.edit');
    Route::put('/admin/hotels/update/{hotel}',  [HotelController::class, 'update'])->name('admin.hotels.update');
    Route::get('/admin/hotels/index',  [HotelController::class, 'index'])->name('admin.hotels.index');
    Route::delete('/admin/hotels/destroy/{hotel}',  [HotelController::class, 'destroy'])->name('admin.hotels.destroy');
    Route::get('/admin/restaurants/create',  [RestaurantController::class, 'create'])->name('admin.restaurants.create');
    Route::post('/admin/restaurants/store',  [RestaurantController::class, 'store'])->name('admin.restaurants.store');
    Route::get('/admin/restaurants/edit/{restaurant}',  [RestaurantController::class, 'edit'])->name('admin.restaurants.edit');
    Route::put('/admin/restaurants/update/{restaurant}',  [RestaurantController::class, 'update'])->name('admin.restaurants.update');
    Route::get('/admin/restaurants/index',  [RestaurantController::class, 'index'])->name('admin.restaurants.index');
    Route::delete('/admin/restaurants/destroy/{restaurant}',  [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
    Route::get('/admin/guides/pending', [AdminController::class, 'pendingGuides'])->name('admin.guides.pending');
    Route::post('/admin/guides/{guide}/confirm', [AdminController::class, 'confirmGuide'])->name('admin.guides.confirm');
    Route::delete('/admin/guides/{guide}', [AdminController::class, 'deleteGuide'])->name('admin.guides.delete');
    Route::get('/admin/trips/create', [AdminController::class, 'createPublicTrip'])->name('admin.trips.create');
    Route::post('/admin/trips', [AdminController::class, 'storePublicTrip'])->name('admin.trips.store');
    Route::get('/admin/trips', [AdminController::class, 'listTrips'])->name('admin.trips.index');
    Route::get('/admin/trips/{trip}/assign-guide', [AdminController::class, 'assignGuideForm'])->name('admin.trips.assign_guide_form');
    Route::post('/admin/trips/{trip}/assign-guide', [AdminController::class, 'assignGuide'])->name('admin.trips.assign_guide');
    Route::get('/admin/wallet/charges', [AdminController::class, 'pendingWalletCharges'])->name('admin.wallet.charges');
    Route::post('/admin/wallet/charges/{transaction}/confirm', [AdminController::class, 'confirmWalletCharge'])->name('admin.wallet.charges.confirm');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/places/create', [AdminController::class, 'createPlace'])->name('admin.places.create');
    Route::post('/admin/places', [AdminController::class, 'storePlace'])->name('admin.places.store');
    Route::get('/admin/trips/{trip}/reviews', [AdminController::class, 'tripReviews'])->name('admin.trips.reviews');
    Route::get('/admin/guides/all', [AdminController::class, 'allGuides'])->name('admin.guides.all');
    Route::get('/admin/tourists/all', [AdminController::class, 'allTourists'])->name('admin.tourists.all');
    // Add more admin routes here
});


