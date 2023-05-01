<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['show','login']);
    }

    public function login(Request $request) {
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);
        if (Auth::attempt($data)) {
            return redirect('/');
        }

        return redirect()->back()->withErrors(['message' => 'Ошибка логина или пароля']);
    }

    public function logout(Request $request) {
        auth()->guard('web')->logout();
        $request->session()->invalidate();

        return redirect('/auth');
    }

    public function show() {
        return view('layouts.auth');
    }
}
