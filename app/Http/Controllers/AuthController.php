<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect(route('dashboard'));
        } else {
            return view('login');
        }
    }

    public function auth(Request $request)
    {   
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            $user = Auth::user();
            switch (true) {
                //TODO: Redirect to intended route after login
                case $user->hasRole('owner'):
                    return redirect()->route('dashboard');
                case $user->hasRole('admin'):
                    return redirect()->route('dashboard');
                case $user->hasRole('finance'):
                    return redirect()->route('dashboard');
                case $user->hasRole('client'):
                    return redirect()->route('dashboard');
                default:
                    Auth::logout();
                    return back()->with('login-error', 'Unauthorized role.');
            }
        } else {
            return back()->with('login-error', 'Email & Password tidak sesuai! Silahkan coba lagi.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
