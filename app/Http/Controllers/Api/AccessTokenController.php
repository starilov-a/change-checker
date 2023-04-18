<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccessTokenController extends Controller
{
    public function store(\App\Http\Requests\AuthLoginRequest $request) {
        $data = $request->validated();

        $user = User::where('login', $data['login'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response(['message' => 'Не удалось авторизоваться'], '422');
        }

        return ['token' => $user->createToken($data['login'])->plainTextToken];
    }
}
