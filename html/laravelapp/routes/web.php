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
    Route::get('/users', 'UserController@selectLoginUser'); // ログインユーザ取得
    Route::get('/users/list', 'UserController@selectAll'); // ユーザ一覧取得
    Route::post('/logout', 'UserController@logout'); // ログアウト
    Route::delete('/users', 'UserController@deleteLoginUser'); // ユーザ削除
    Route::patch('/users', 'UserController@updateUser'); // ユーザ編集ができる事

    Route::get('/threads', 'ThreadController@select'); // スレッド取得(一覧or検索)
    Route::post('/threads', 'ThreadController@create'); // スレッド作成

    Route::get('/replies', 'ReplyController@selectByThreadId'); // リプライ取得。スレッド指定
    Route::post('/replies', 'ReplyController@create'); // リプライ作成
    Route::delete('/replies', 'ReplyController@deleteOwnReply'); // リプライ削除
    // TODO: リプライ編集ができる事。内容。
    // TODO: リプライ検索(スレッド指定ありなし両方)
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
