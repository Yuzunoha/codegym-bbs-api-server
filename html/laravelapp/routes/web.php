<?php

use App\Models\Reply;
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
    Route::get('/users', 'UserController@selectAll');
    Route::post('/logout', 'UserController@logout');
    Route::get('/threads', 'ThreadController@selectAll');
    Route::post('/threads', 'ThreadController@create');
    Route::get('/replies', 'ReplyController@selectAll');
    Route::post('/replies', 'ReplyController@create');
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
