<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admins')->check() and Auth::guard('admins')->user()->role === 'admin') {
            config(['adminlte.menu' => config('adminlte.menu.admin')]);
            \Log::channel('custom')->info('admin');
            return $next($request);
        }
        return redirect()->route('logins.show');
    }
}
