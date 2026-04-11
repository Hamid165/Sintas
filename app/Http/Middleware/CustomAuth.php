<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, $next)
    {
        if (!session('isLoggedIn')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
