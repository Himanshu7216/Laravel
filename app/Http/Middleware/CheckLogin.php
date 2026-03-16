<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

          // If user not logged in and trying to access protected pages
        if (!Auth::check() && !$request->is('login') && !$request->is('signup')) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // If user already logged in and trying to access login/signup
        if (Auth::check() && ($request->is('login') || $request->is('signup'))) {
            return redirect('/home');
        }

        return $next($request);
    }
}
