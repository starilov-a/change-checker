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
Route::get('auth', 'App\Http\Controllers\Web\AuthController@show')->name('web.auth.show');
Route::post('auth', 'App\Http\Controllers\Web\AuthController@login')->name('web.auth.login');

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::resource('sites', \App\Http\Controllers\Web\SiteController::class)->names('web.sites');
    Route::post('sites/scan', '\App\Http\Controllers\Web\SiteController@scan')->name('web.sites.scan');
    Route::post('sites/searchpage', '\App\Http\Controllers\Web\SiteController@searchpage')->name('web.sites.searchpage');
    Route::post('sites/searchsite', '\App\Http\Controllers\Web\SiteController@searchsite')->name('web.sites.searchsite');


    Route::get('changes/', '\App\Http\Controllers\Web\ChangeController@index')->name('web.changes.index');
    Route::post('changes/searchsite', '\App\Http\Controllers\Web\ChangeController@searchchangesite')->name('web.changes.searchsite');
    Route::get('changes/{site}', '\App\Http\Controllers\Web\ChangeController@show')->name('web.changes.show');

    Route::get('excludedpages/', 'App\Http\Controllers\Web\ExcludedPageController@index')->name('web.excludedpages.index');
    Route::get('excludedpages/{site}', '\App\Http\Controllers\Web\ExcludedPageController@show')->name('web.excludedpages.show');

    Route::get('logout', '\App\Http\Controllers\Web\AuthController@logout')->name('web.auth.logout');
});


Route::get('/', function () {
    return redirect('/sites');
})->name('home');
