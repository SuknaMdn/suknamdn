<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('developer/login'); // Redirect to login if not authenticated
        }

        if (Auth::user()->role === 'developer') {
            return redirect('/developer'); // Redirect to /developer if the user is a developer
        }

        if (Auth::user()->role !== $role) {
            dd(Auth::user()->role, $role);
            return redirect('/home'); // Redirect to home or another page if the role doesn't match
        }

        return $next($request);
    }
}
