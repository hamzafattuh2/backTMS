<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsGuide
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && ($user->type === 'guide' && $user->tourGuide)) {
            return $next($request);
        }
        abort(403, 'Unauthorized22');
    }

    //تابع ثاني
    //    public function handle(Request $request, Closure $next): Response
    // {
    //     $user = auth()->user();

    //     if (!$user || !$user->tourGuide) {
    //         abort(403, 'Unauthorized guide');
    //     }

    //     $conf = $user->tourGuide->confirmByAdmin;

    //     if ($conf === 1) {
    //         return $next($request);
    //     }

    //     abort(403, 'Admin did not confirm guide.');
    // }
}
