<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ChangeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('test', 'App\Http\Controllers\TestController@index');

Route::post('login', 'App\Http\Controllers\Api\AuthController@login')->name('auth.login');

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::apiResource('sites', SiteController::class);
    Route::apiResource('pages', PageController::class);
    Route::apiResource('changes', ChangeController::class);
    Route::post('register', 'App\Http\Controllers\Api\AuthController@register')->name('auth.register')->middleware('IsAdmin');
    Route::post('accesstoken', 'App\Http\Controllers\Api\AccessTokenController@store')->name('auth.token')->middleware('IsAdmin');
    Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout')->name('auth.logout');
});

