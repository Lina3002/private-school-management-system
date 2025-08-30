<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has admin role or is super admin
        if (!$user->role || !in_array($user->role->name, ['admin', 'super_admin'])) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
} 