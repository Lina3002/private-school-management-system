<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsSuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->role || Auth::user()->role->name !== 'super_admin') {
            abort(403, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
