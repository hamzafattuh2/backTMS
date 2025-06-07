<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    //
    public function updateFcmToken(Request $request)
{
    $request->validate(['token' => 'required|string']);

    $request->user()->update([
        'fcm_token' => $request->token
    ]);

    return response()->json(['message' => 'Token updated']);
}
}
