<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginview(){
        return view('auth.login');
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|exists:users',
            'password' => 'required'
        ]);
        $credentials = $request->only(['email', 'password']);
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
    }
    public function registerview(){
        return view('auth.register');
    }
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        auth()->login($user);
        return redirect('/');
    }
    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }
}
