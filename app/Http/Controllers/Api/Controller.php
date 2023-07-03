<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="API Monitor",
 *     @OA\Contact(name="Glassen_IT")
 * )
 * @OA\Server(
 *     url="http://62.113.103.54/api",
 *     description="API Monitor"
 * )
 * @OA\Schema(
 *     schema="ErrorResult422",
 *     title="Шаблон ошибки",
 * 	   @OA\Property(
 *         property="code",
 *         type="int"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string"
 *     ),
 *     example={"code": 422, "message": "Неверные входные значения"}
 * )
 * @OA\Schema(
 *     schema="ErrorResult401",
 *     title="Шаблон ошибки статуса авторизации",
 *     @OA\Property(
 *         property="message",
 *         type="string"
 *     ),
 *     example={"message": "Unauthenticated."}
 * )
 */

class Controller extends \App\Http\Controllers\Controller
{
}
