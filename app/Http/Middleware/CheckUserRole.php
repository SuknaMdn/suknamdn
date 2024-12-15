<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->hasRole('developer')) {
            return $next($request);
        }
        if (auth()->user()->hasRole('admin')) {
            return $next($request);
        } else {
            auth()->logout();
            session()->flash('error', 'ليس لديك صلاحية للدخول إلى هذه الصفحة');
            return redirect()->route('login');
        }
    }
}
