<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth:sanctum')->get('/threads', 'ThreadController@getAll');
Route::middleware('auth:sanctum')->post('/threads', 'ThreadController@create');
Route::middleware('auth:sanctum')->get('/replies', 'ReplyController@selectAll');
Route::middleware('auth:sanctum')->post('/replies', 'ReplyController@create');
