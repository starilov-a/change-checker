<?php

namespace App\Http\Controllers\Api;

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

    /**
     * @OA\Tag(
     *     name="auth",
     *     description="Авторизация"
     * )
     * @OA\Post(
     *     path="/register",
     *     tags={"auth"},
     *     summary="Регестрация нового пользователя",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="login",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string"
     *                 ),
     *                 example={"login": "user1", "password": "password1", "email": "example@gmail.com", "role": "0|1|2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="login",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 example={"login":"testmanager","email":"testexample@mail.ru","role":"2","updated_at":"2023-06-26T11:06:30.000000Z","created_at":"2023-06-26T11:06:30.000000Z","id":4}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult401"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Неверные входные значения",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult422"),
     *         )
     *     )
     * )
     */

    public function register(\App\Http\Requests\AuthRegisterRequest $request) {
        $user = User::create($request->validated());

        return response($user, \Illuminate\Http\Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"auth"},
     *     summary="Аутентификация пользователя",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="login",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"login": "user1", "password": "password1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="data",
     *                type="array",
     *                example={{
     *                  "login": "admin",
     *                  "email": "anton_starilov@mail.ru",
     *                  "role": "1",
     *                }},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="login",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="email",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="role",
     *                         type="int"
     *                      ),
     *                ),
     *             ),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Неверный логин или пароль",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult422"),
     *         )
     *     )
     * )
     */

    public function login(\App\Http\Requests\AuthLoginRequest $request) {
        if (Auth::attempt($request->validated())) {
            return AuthResource::collection(collect([$request->user()]));
        }
        return response(['message' => 'Неверные входные значения'], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @OA\Get(
     *     path="/logout",
     *     tags={"auth"},
     *     summary="Заверешние сеанса пользователя",
     *     @OA\Response(
     *         response=204,
     *         description="OK",
     *     ),
     * )
     */

    public function logout(Request $request) {
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        return response()->noContent();
    }

}
