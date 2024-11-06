<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExceptRole
{
    public function handle($request, Closure $next, $role)
    {
        // Check if user is authenticated and has the restricted role
        if (Auth::check() && Auth::user()->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}