<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/auth', 'App\Http\Controllers\AuthController@show')->name('web.auth.show');
Route::post('/auth', 'App\Http\Controllers\AuthController@login')->name('web.auth.login');

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::resource('sites', \App\Http\Controllers\SiteController::class)->names('web.sites');
    Route::resource('changes', \App\Http\Controllers\ChangeController::class)->names('web.changes');
    Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('web.auth.logout');
});


Route::get('/', function () {
    return redirect('/sites');
})->name('home');
