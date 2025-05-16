<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'stars' => 'required|integer|between:1,5',
            'amenities' => 'nullable',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Ensure amenities is JSON encoded if needed
        $validated['amenities'] = json_encode($request->amenities ?? []);

        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'stars' => 'required|integer|between:1,5',
            'amenities' => 'nullable',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['amenities'] = json_encode($request->amenities ?? []);

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully');
    }
}
