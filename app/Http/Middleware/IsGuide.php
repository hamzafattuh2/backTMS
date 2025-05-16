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
        if ($user && ($user->type === 'guide' || $user->tourGuide)) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
} 