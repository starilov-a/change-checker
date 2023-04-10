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


Route::post('accesstoken', 'App\Http\Controllers\Api\AccessTokenController@store')->name('auth.token');
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::apiResource('sites', SiteController::class);
    Route::apiResource('pages', PageController::class);
    Route::apiResource('changes', ChangeController::class);
});

