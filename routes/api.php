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

Route::post('login', 'App\Http\Controllers\Api\AuthController@login')->name('api.auth.login');

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::apiResource('sites', SiteController::class)->names('api.sites');
    Route::post('sites/scan', 'App\Http\Controllers\Api\SiteController@scan')->name('api.sites.scan');
    Route::post('sites/search', 'App\Http\Controllers\Api\SiteController@search')->name('api.sites.search');

    Route::apiResource('pages', PageController::class)->names('api.pages');

    Route::apiResource('changes', ChangeController::class)->names('api.changes');

    Route::post('register', 'App\Http\Controllers\Api\AuthController@register')->name('api.auth.register')->middleware('IsAdmin');
    Route::post('accesstoken', 'App\Http\Controllers\Api\AccessTokenController@store')->name('api.auth.token')->middleware('IsAdmin');
    Route::get('logout', 'App\Http\Controllers\Api\AuthController@logout')->name('api.auth.logout');
});

