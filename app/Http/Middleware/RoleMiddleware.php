<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (session('role') !== $role) {
            return redirect()->route('role.select');
        }

        return $next($request);
    }
}
