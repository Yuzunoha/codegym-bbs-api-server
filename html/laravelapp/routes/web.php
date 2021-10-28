<?php

use Illuminate\Http\Request;
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

Route::post('/login', 'UserController@login')->name('login');
Route::post('/register', 'UserController@register');
Route::group(['middleware' => ['auth:sanctum', 'RequestFilter']], function () {
    Route::post('/logout', 'UserController@logout');
    Route::get('/users/{id}', 'UserController@selectById');
    Route::get('/users', 'UserController@select');
    Route::delete('/users', 'UserController@deleteLoginUser');
    Route::patch('/users', 'UserController@updateUser');
    Route::post('/threads', 'ThreadController@create');
    Route::get('/threads', 'ThreadController@select');
    Route::get('/threads/{id}', 'ThreadController@selectById');
    Route::patch('/threads/{id}', 'ThreadController@updateOwnThread');
    Route::get('/replies', 'ReplyController@selectByThreadId');
    Route::get('/replies/{id}', 'ReplyController@selectById');
    Route::post('/replies', 'ReplyController@create');
    Route::delete('/replies/{id}', 'ReplyController@deleteOwnReply');
    Route::patch('/replies/{id}', 'ReplyController@updateOwnReply');
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
