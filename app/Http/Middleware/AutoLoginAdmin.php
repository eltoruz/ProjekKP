<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::find(1);

            if (!$user) {
                $user = User::create([
                    'name' => 'Admin',
                    'email' => 'admin@pusdatin.local',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                ]);
            }

            Auth::login($user);
        }

        return $next($request);
    }
}
