<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsParent
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via session (parent login)
        if (!$request->session()->has('parent_id') || $request->session()->get('user_type') !== 'parent') {
            return redirect()->route('login')->withErrors(['email' => 'Access denied. Parent login required.']);
        }

        return $next($request);
    }
} 