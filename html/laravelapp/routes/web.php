<?php

use App\Models\Reply;
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

Route::post('/login',                                 'UserController@login')->name('login');
Route::post('/register',                              'UserController@register');
Route::middleware('auth:sanctum')->get('/users',      'UserController@selectAll');
Route::middleware('auth:sanctum')->post('/logout',    'UserController@logout');
Route::middleware('auth:sanctum')->get('/threads',  'ThreadController@selectAll');
Route::middleware('auth:sanctum')->post('/threads', 'ThreadController@create');
Route::middleware('auth:sanctum')->get('/replies',   'ReplyController@selectAll');
Route::middleware('auth:sanctum')->post('/replies',  'ReplyController@create');

Route::get('/test', function () {
    dd(
        Reply::where('thread_id', 1)->count()

    );
    return 'テストです';
});
