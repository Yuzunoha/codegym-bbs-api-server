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

Route::post('/login', 'UserController@login')->name('login'); // ログイン(トークン発行)
Route::post('/register', 'UserController@register'); // ユーザ登録

Route::group(['middleware' => ['auth:sanctum', 'RequestFilter']], function () {
    Route::get('/users', 'UserController@selectAll'); // ユーザ一覧取得
    Route::post('/logout', 'UserController@logout'); // ログアウト
    Route::get('/threads', 'ThreadController@selectAll'); // スレッド一覧取得
    Route::post('/threads', 'ThreadController@create'); // スレッド作成
    Route::get('/replies', 'ReplyController@selectAll'); // リプライ一覧取得
    Route::post('/replies', 'ReplyController@create'); // リプライ作成
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
