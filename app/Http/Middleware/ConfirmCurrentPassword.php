<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class ConfirmCurrentPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تأكد أن الحقل موجود
        if (!$request->filled('current_password')) {
            return response()->json([
                'message' => 'Current password is required.'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        // تحقق من التطابق
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.'
            ], 403);
        }

        return $next($request);
    }
}
