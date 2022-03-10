<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\AuthController;

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
Route::post('register', 'App\Http\Controllers\API\AuthController@register')->name('register');
Route::post('login', 'App\Http\Controllers\API\AuthController@login')->name('login');
Route::post('password/email', 'App\Http\Controllers\API\AuthController@forgot')->name('forgot');
Route::post('password/reset', 'App\Http\Controllers\API\AuthController@reset')->name('reset');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout')->name('logout');

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

