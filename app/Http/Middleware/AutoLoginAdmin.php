<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            Auth::loginUsingId(1);
        }

        return $next($request);
    }
}
