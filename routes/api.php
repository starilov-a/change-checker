<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;

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

Route::post('/addsite',[SiteController::class, 'store']);
Route::post('/getsite',[SiteController::class, 'show']);
Route::post('/getsitelist',[SiteController::class, 'index']);
Route::post('/removesite',[SiteController::class, 'destroy']);
Route::post('/updatesite',[SiteController::class, 'update']);
