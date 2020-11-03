<?php

use Illuminate\Http\Request;
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
Route::post('login', 'ApiAuthController@login');
Route::post('register', 'UserController@register');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'ApiAuthController@logout');
    Route::get('conversations', 'ConversationController@index');
    Route::get('conversations/store', 'ConversationController@store');
    Route::get('conversations/read', 'ConversationController@conversationAsReaded');
    Route::post('messages/store', 'MessageController@store');
});