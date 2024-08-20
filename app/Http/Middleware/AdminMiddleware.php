<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
            return $next($request);
        }elseif (Auth::guard('admins')->check() and Auth::guard('admins')->user()->role === 'sales') {
            config(['adminlte.menu' => config('adminlte.menu.sales')]);
            return $next($request);
        }elseif (Auth::guard('admins')->check() and Auth::guard('admins')->user()->role === 'support') {
                config(['adminlte.menu' => config('adminlte.menu.Support')]);
                return $next($request);
            }
        \Log::channel('custom')->info('admin sadfsaf');

        return redirect()->route('logins.show');
        }
    }

