<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(\App\Http\Requests\AuthRegisterRequest $request) {
        $data = $request->validated();

        $user = User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password']
        ]);

        return response($user, \Illuminate\Http\Response::HTTP_CREATED);
    }

    public function login(\App\Http\Requests\AuthLoginRequest $request) {
        $request->session()->put('key', 'value');
        if (Auth::attempt($request->validated())) {
            return response($request->user(), \Illuminate\Http\Response::HTTP_OK);
        }
        return response('Неверный логин или пароль', \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
