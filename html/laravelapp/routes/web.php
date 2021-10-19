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
/*
method: post
body:
  - email
  - password
response:
  - トークン
*/

Route::post('/register', 'UserController@register'); // ユーザ登録
/*
method: post
body:
  - name
  - email
  - password
response:
  - 作成したモデル
*/

Route::group(['middleware' => ['auth:sanctum', 'RequestFilter']], function () {
    /*
    以下全てgetメソッド
    users      ユーザ一覧or検索(keyword)
    users/3    ユーザ一件
    threads    スレッド一覧or検索(keyword)
    threads/3  スレッド一件
    replies    リプライ検索(thread_id or/and keyword)
    replies/3  リプライ一件
    */

    Route::post('/logout', 'UserController@logout'); // ログアウト
    /*
    概要: ログインユーザのトークンをサーバから削除する
    header: トークン
    response:
      - メッセージ
    */

    Route::get('/users/{id}', 'UserController@selectById'); // ユーザ1件取得
    /*
    概要: id指定でユーザを取得する
    header: トークン
    url parameter:
      - id
        - ユーザid
    response:
      - ユーザ
    */

    Route::get('/users/auth', 'UserController@selectAuth'); // ログインユーザ取得
    /*
    header: トークン
    response:
      - ログインユーザ
    */

    Route::get('/users', 'UserController@select'); // ユーザ取得
    /*
    header: トークン
    body:
      - q (任意)
        - nameをあいまい検索するキーワード
    response:
      - モデルのリスト
    */

    Route::delete('/users', 'UserController@deleteLoginUser'); // ユーザ削除
    /*
    概要: ログインユーザを削除する
    header: トークン
    response:
      - メッセージ
    */

    Route::patch('/users', 'UserController@updateUser'); // ユーザ編集ができる事
    /*
    概要: ログインユーザを編集する
    header: トークン
    body:
      - name
    response:
      - 編集後のモデル
    */

    Route::get('/threads', 'ThreadController@select'); // スレッド取得(一覧or検索)
    /*
    header: トークン
    query string:
      - q (任意)
        - titleをあいまい検索するキーワード
    response:
      - モデルのリスト
    */

    Route::post('/threads', 'ThreadController@create'); // スレッド作成
    /*
    概要: スレッドを作成する
    header: トークン
    body:
      - title
    response:
      - 作成したスレッド
    */
    // TODO: スレッド単体取得
    // TODO: リプライ単体取得

    Route::get('/replies', 'ReplyController@selectByThreadId'); // リプライ取得。スレッド指定
    Route::post('/replies', 'ReplyController@create'); // リプライ作成
    Route::delete('/replies', 'ReplyController@deleteOwnReply'); // リプライ削除
    Route::patch('/replies', 'ReplyController@updateOwnReply'); // リプライ編集
    // TODO: リプライ検索(スレッド指定ありなし両方)
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
