<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TourGuide;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $guides = \App\Models\TourGuide::with('user')->where('confirmByAdmin', true)->get();
        return response()->json(['guides' => $guides]);
    }
    public function registerGuide(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
            'type'=>'nullable|string',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'languages' => 'required|string',
            'years_of_experience' => 'required|integer',
            'license_picture_path' => 'nullable|file|mimes:jpeg,png,pdf',
            'cv_path' => 'nullable|file|mimes:pdf',
        ]);
        $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public/profile_image');
            $profilePicturePath = str_replace('public/', '', $path);
        }
        $user = User::create([
            'user_name' => $validated['user_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'guide',
            'phone_number' => $validated['phone_number'],
            'profile_image' => $profilePicturePath ?? null,
            'gender'=> $validated['gender'],
            'birth_date' => $validated['birth_date'],
        ]);
        $licensePath = null;
        if ($request->hasFile('license_picture_path')) {
            $licensePath = $request->file('license_picture_path')->store('public/license');
            $licensePath = str_replace('public/', '', $licensePath);
        }
        $cvPath = null;
        if ($request->hasFile('cv_path')) {
            $cvPath = $request->file('cv_path')->store('public/cv');
            $cvPath = str_replace('public/', '', $cvPath);
        }
        $guide = TourGuide::create([
            'user_id' => $user->id,
            'languages' => $validated['languages'],
            'years_of_experience' => $validated['years_of_experience'],
            'license_picture_path' => $licensePath,
            'cv_path' => $cvPath,
            'guide_picture_path' => $profilePicturePath,
        ]);
        $token = $user->createToken('guide_auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Guide registered successfully',
            'user' => $user,
            'guide' => $guide,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }
    public function loginGuide(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->type != 'guide') {
            return response()->json(['message' => 'You are not a guide'], 401);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
        $token = $user->createToken('guide_auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }
    public function logoutGuide(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }
} 