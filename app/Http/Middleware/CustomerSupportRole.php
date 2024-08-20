<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CustomerSupportRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admins')->check() and (Auth::guard('admins')->user()->role === 'support' or Auth::guard('admins')->user()->role === 'admin')) {
            if (Auth::guard('admins')->user()->role === 'support'){
                config(['adminlte.menu' => config('adminlte.menu.Support')]);
            }else{
                config(['adminlte.menu' => config('adminlte.menu.admin')]);
            }

            return $next($request);
        }
        return redirect()->route('logins.show');
    }
}
