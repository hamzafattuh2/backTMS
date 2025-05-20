<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTourist
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && ($user->type === 'tourist' && $user->tourist)) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}
