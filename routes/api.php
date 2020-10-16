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
            Route::get('/todo/{id}', 'API\Todo\TodoController@getSingleTodo');
            Route::post('/todos', 'API\Todo\TodoController@store');
            Route::Delete('/todos/{id}', 'API\Todo\TodoController@destroy');
            Route::Put('/todos/{id}', 'API\Todo\TodoController@update');
        });
        Route::get('/logout', 'API\Auth\AuthController@logout');
    });
    Route::get('/verify-account/{token}', 'API\Auth\AuthController@verifyAccount')->name('verify-account');
    Route::post('/register', 'API\Auth\AuthController@register')->name('register');
    Route::get('/account-request-verification/{token}', 'API\Auth\AuthController@requestVerification')->name('request-verification');
    Route::post('/login', 'API\Auth\AuthController@login')->name('login');
    Route::post('/forgot-password', 'API\Auth\AuthController@forgotPassword')->name('forgot-password');
    Route::post('/change-password', 'API\Auth\AuthController@changePassword')->name('change-password');
    Route::get('/error', 'API\Auth\AuthController@error')->name('error');

