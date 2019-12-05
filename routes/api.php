<?php


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

use Illuminate\Http\Request;

Route::post('users/store', 'UserController@store');
Route::post('login', 'UserController@login');
Route::get('users/show/{user}', 'UserController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('passwords', 'PasswordController');
    Route::apiResource('users', 'UserController');
});