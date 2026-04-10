<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }
        
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif (auth()->user()->role === 'staff') {
                return redirect('/staff/dashboard');
            }
        }

        return redirect('/login');
    }
}
