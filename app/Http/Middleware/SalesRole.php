<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SalesRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admins')->check() and (Auth::guard('admins')->user()->role === 'sales' or Auth::guard('admins')->user()->role === 'admin')) {
            if (Auth::guard('admins')->user()->role === 'sales') {  
                config(['adminlte.menu' => config('adminlte.menu.sales')]);
                \Log::channel('custom')->info('false');
            } else {
                config(['adminlte.menu' => config('adminlte.menu.admin')]);
            }
            return $next($request);
        }
        return redirect()->route('logins.show');
    }
}
