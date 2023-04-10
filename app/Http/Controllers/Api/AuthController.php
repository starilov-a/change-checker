<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request) {
        $user = User::create($request->validated());
        return response($user, \Illuminate\Http\Response::HTTP_CREATED);
     }
}
