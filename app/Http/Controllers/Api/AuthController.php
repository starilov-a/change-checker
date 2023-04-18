<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only('login');
    }

    public function register(\App\Http\Requests\AuthRegisterRequest $request) {
        $user = User::create($request->validated());

        return response($user, \Illuminate\Http\Response::HTTP_CREATED);
    }

    public function login(\App\Http\Requests\AuthLoginRequest $request) {
        if (Auth::attempt($request->validated())) {
            return AuthResource::collection(collect([$request->user()]));
        }
        return response(['message' => 'Неверный логин или пароль'], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function logout(Request $request) {
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        return response()->noContent();
    }

}
