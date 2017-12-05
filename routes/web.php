<?php

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

Route::get('/', 'PageController@index');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/{type}/{id}', 'DashboardController@show');


Route::resource('/messages','MessagesController');
Route::get('/messages/getMore/{id}/{date}','MessagesController@getMore');
Route::resource('/games','GameController');
Route::post('/game/invite/{id}', 'GameController@invite');
Route::post('/game/accept/{id}', 'GameController@accept');
Route::get('/game/challenge/{id}', 'GameController@getChallenges');
Route::get('/game/board/{id}','GameController@getBoardData');