<?php

namespace Modules\Register\App\Http\Controllers;

use App\DataTables\registersDataTable;
use App\Http\Controllers\Controller;
use App\Models\adminusers;
use App\Models\register;
use Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function show(Request $request){
        return view('register::register',['roles'=>adminusers::$roles]);
    }


    public function register(Request $request){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        if ($request['password'] === $request['confirm_password']){
                $user = register::where('email', $request['email'])->first();
                if ($user) {
                    return redirect()->route('register.show')->with('status', 'user already exists');
                }
                $abc = $stripe->customers->create([
                    'name' => $request['username'],
                    'email' => $request['email'],
                ]);
                
                register::create(['username'=>$request['username'],'email'=>$request['email'],'password'=>Hash::make($request['password']),'StripeCustomerId'=>$abc->id]);
            }
        return redirect()->route('logins');
    }

public function checkPasswords(Request $request) {
    $password = $request->password;
    $confirmPassword = $request->confirmPassword;

    if($password === $confirmPassword) {
        return response()->json(['match' => true]);
    } else {
        return response()->json(['match' => false]);
    }
}

    public function index(registersDataTable $dataTable){
        return $dataTable->render('datatables.registers');
    }

    public function index2(registersDataTable $dataTable){
       return $dataTable->render('admin::components.admin.customers');
    }

    public function edit($id)
    {
        $register = Register::findOrFail($id);
        return view('registers.edit', compact('register'));
    }

    public function update(Request $request, $id)
    {
        $register = Register::findOrFail($id);
        $register->update($request->all());
        return redirect()->route('registers.index');
    }

    public function destroy($id)
    {
        Register::destroy($id);
        return redirect()->route('admin.viewcustomers');
    }

    public function adminRegisterVerification(Request $request){
        
        if ($request['password'] === $request['confirm_password']) {
                $user = adminusers::where('email', $request['email'])->first();
                if ($user) {
                    return redirect()->route('admin.Register')->with('status', 'user already exists');
                }
                adminusers::create(['username' => $request['username'], 'email' => $request['email'], 'password' => Hash::make($request['password']), 'role' => $request['role'][0]]);
            return redirect()->route('admin.Register');
        }
    }
}
