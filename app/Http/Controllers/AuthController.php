<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function form(){
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } 
            if ($user->role === 'staff') {
                return redirect('/staff/dashboard');
            }
        }
        return back()->withErrors(['email' => 'Email atau password salah!'])->withInput();
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
