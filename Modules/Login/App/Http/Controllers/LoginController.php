<?php

namespace Modules\Login\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\customer;
use App\Models\register;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\adminusers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function show(Request $request)
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        Auth::logout();
        $request->session()->invalidate();
        
        if (Auth::guard('users')->attempt($credentials)) { 
            $request->session()->regenerate();
            $request->session()->put('username', Auth::guard('users')->user()->username);
            $request->session()->put('email', Auth::guard('users')->user()->email);
            $request->session()->put('id', Auth::guard('users')->user()->id);
            return redirect()->route('movie.show');
        } else {
            return back()->withErrors([
                'incorrect_credentials' => true,
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function adminLogin(Request $request){
        return view('admin::admin.adminLogin');
    }

    public function adminRegister(Request $request){
        return view('admin::admin.registerAdmin', ['roles' => adminusers::$roles]);
    }

    public function adminLoginVerification(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        Auth::logout();
        $request->session()->invalidate();
        if (Auth::guard('admins')->attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('username', Auth::guard('admins')->user()->username);
            $request->session()->put('email', Auth::guard('admins')->user()->email);
            $request->session()->put('id', Auth::guard('admins')->user()->id);
            return redirect()->route('admin.panel');
        } else {
            return redirect()->route('admin.login')->withErrors([
                'incorrect_credentials' => true,
            ])->onlyInput('email');
        }
    }
}
