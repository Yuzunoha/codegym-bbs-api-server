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
    - 最大16文字
  - email
  - password
response:
  - モデル
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
    response:
      - モデル
    */

    Route::get('/users', 'UserController@select'); // ユーザ取得
    /*
    header: トークン
    body:
      - q (任意)
        - nameとemailを同時にあいまい検索するキーワード
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
        - 最大16文字
    response:
      - モデル
    */

    Route::post('/threads', 'ThreadController@create'); // スレッド作成
    /*
    概要: スレッドを作成する
    header: トークン
    body:
      - title
        - 最大64文字
    response:
      - モデル
    */

    Route::get('/threads', 'ThreadController@select'); // スレッド取得(一覧or検索)
    /*
    header: トークン
    query string:
      - q (任意)
        - titleとip_addressを同時にあいまい検索するキーワード
    response:
      - モデルのリスト
    */

    Route::get('/threads/{id}', 'ThreadController@selectById'); // スレッド取得(単体)
    /*
    概要: id指定でスレッドを取得する。レスは含まない
    header: トークン
    url parameter:
      - id
    response:
      - モデル
    */

    Route::get('/replies', 'ReplyController@selectByThreadId'); // リプライ取得。スレッド指定
    /*
    概要: スレッドid指定でリプライ一覧を降順で取得する
    header: トークン
    query string:
      - thread_id
      - q (任意)
        - textとip_addressを同時にあいまい検索するキーワード(投稿者の情報は検索対象に含まれない)
    response:
      - モデルのリスト
    */

    Route::get('/replies/{id}', 'ReplyController@selectById'); // リプライ取得(単体)
    /*
    概要: id指定でリプライを取得する
    header: トークン
    url parameter:
      - id
    response:
      - モデル
    */

    Route::post('/replies', 'ReplyController@create'); // リプライ作成
    /*
    概要: リプライを作成する
    header: トークン
    body:
      - thread_id
      - text
        - 最大1024文字
    response:
      - モデル
    */

    Route::delete('/replies', 'ReplyController@deleteOwnReply'); // リプライ削除
    /*
    概要: リプライを削除する。他人のリプライは編集できない
    header: トークン
    body:
      - thread_id
    response:
      - メッセージ
    */

    Route::patch('/replies', 'ReplyController@updateOwnReply'); // リプライ編集
    /*
    概要: リプライを編集する。他人のリプライは編集できない
    header: トークン
    body:
      - thread_id
      - text
        - 最大1024文字
    response:
      - モデル
    */
});

Route::group(['middleware' => ['RequestFilter']], function () {
    Route::get('/test', function (Request $req) {
        return $req;
    });
});
