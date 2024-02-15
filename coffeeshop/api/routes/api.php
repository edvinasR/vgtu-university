<?php

use Illuminate\Http\Request;

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
Route::post('login', 'AuthenticationController@login');
Route::post('register', 'AuthenticationController@register');
Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('logout', 'AuthenticationController@logout');
    Route::get('user', 'AuthenticationController@user');
    Route::apiResource('coffees', 'CoffeeController');
});

