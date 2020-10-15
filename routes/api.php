<?php

use Illuminate\Support\Facades\Route;


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

    Route::middleware('auth:api')->group(function(){
        Route::middleware('verify')->group(function (){
            Route::get('/todos', 'API\Todo\TodoController@index');
            Route::post('/todos', 'API\Todo\TodoController@store');
            Route::Delete('/todos/{id}/{todoType}', 'API\Todo\TodoController@destroy');
            Route::Put('/todos/{id}', 'API\Todo\TodoController@update');
        });
        Route::get('/logout', 'API\Auth\AuthController@logout');
    });
    Route::get('/verify-account/{token}', 'API\Auth\AuthController@verifyAccount')->name('verify-account');
    Route::post('/register', 'API\Auth\AuthController@register')->name('register');
    Route::post('/login', 'API\Auth\AuthController@login')->name('login');
    Route::get('/account-request-verification/{token}', 'API\Auth\AuthController@requestVerification')->name('request-verification');
    Route::get('/error', 'API\Auth\AuthController@error')->name('error');

