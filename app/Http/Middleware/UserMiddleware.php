<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
