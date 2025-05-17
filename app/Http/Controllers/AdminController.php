<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TourGuide;
use App\Models\Trip;
use App\Models\WalletTransaction;
use App\Models\Wallet;
use App\Models\TouristPlace;
use App\Models\Feedback;
use App\Models\Tourist;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->type === 'admin' || $user->admin) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'You are not authorized as admin.']);
            }
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        return view('admin.dashboard'); // You need to create this Blade view
    }

    public function pendingGuides()
    {
        $guides = TourGuide::whereNull('confirmByAdmin')->orWhere('confirmByAdmin', false)->get();
        return view('admin.guides.pending', compact('guides'));
    }

    public function confirmGuide(TourGuide $guide)
    {
        $guide->confirmByAdmin = true;
        $guide->save();
        return redirect()->route('admin.guides.pending')->with('success', 'Guide confirmed successfully.');
    }

    public function deleteGuide(TourGuide $guide)
    {
        $user = $guide->user();
        $guide->delete();
        $user->delete();
        // Optionally delete the user as well:
        // $guide->user()->delete();
        return redirect()->route('admin.guides.pending')->with('success', 'Guide deleted successfully.');
    }

    public function createPublicTrip()
    {
        return view('admin.trips.create');
    }

    public function storePublicTrip(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'language_guide' => 'required|string',
            'price' => 'required|numeric',
        ]);
        $trip = Trip::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'language_guide' => $validated['language_guide'],
            'days_count' => (new \DateTime($validated['end_date']))->diff(new \DateTime($validated['start_date']))->days + 1,
            'price' => $validated['price'],
            'status' => 'pending',
            'public_or_private' => 'public',
            'delete_able' => true,
        ]);
        return redirect()->route('admin.trips.index')->with('success', 'Public trip created successfully.');
    }

    public function listTrips()
    {
        $trips = Trip::where('public_or_private', 'public')->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function assignGuideForm(Trip $trip)
    {
        $guides = TourGuide::where('confirmByAdmin', true)->get();
        return view('admin.trips.assign_guide', compact('trip', 'guides'));
    }

    public function assignGuide(Request $request, Trip $trip)
    {
        $request->validate(['guide_id' => 'required|exists:users,id']);
        $trip->guide_id = $request->guide_id;
        $trip->save();
        return redirect()->route('admin.trips.index')->with('success', 'Guide assigned to trip successfully.');
    }

    public function pendingWalletCharges()
    {
        $transactions = WalletTransaction::where('type', 'deposit')->where('confirm_admin',false)->get();
        return view('admin.wallet.charges', compact('transactions'));
    }

    public function confirmWalletCharge(WalletTransaction $transaction)
    {
        $transaction->confirm_admin = true;
        $transaction->save();
        $wallet = Wallet::where('id',$transaction->wallet_id)->first();
        $wallet->balance = $wallet->balance + $transaction->amount;
        $wallet->save();
        return redirect()->route('admin.wallet.charges')->with('success', 'Wallet charge confirmed.');
    }

    public function editProfile()
    {
        $admin = auth()->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string',
            'profile_image' => 'nullable|image',
        ]);
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }
        $user->update($validated);
        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function createPlace()
    {
        return view('admin.places.create');
    }

    public function storePlace(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'features' => 'nullable|string',
            'average_rating' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);
        TouristPlace::create($validated);
        return redirect()->route('admin.places.create')->with('success', 'Tourist place added successfully.');
    }

    public function tripReviews($tripId)
    {
        $reviews = Feedback::where('trip_id', $tripId)->get();
        return view('admin.trips.reviews', compact('reviews'));
    }

    public function allGuides()
    {
        $guides = \App\Models\TourGuide::with('user')->get();
        return view('admin.guides.all', compact('guides'));
    }

    public function allTourists()
    {
        $tourists = User::where('type','tourist')->get();
        return view('admin.tourism.tourism', compact('tourists'));
    }
}
