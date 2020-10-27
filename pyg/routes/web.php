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

Route::get('/', "Home\IndexController@index");

Route::get('/login', "Home\LoginController@login");
Route::post('/dologin', "Home\LoginController@dologin");
Route::get('/logout', "Home\LoginController@logout");
Route::get('/register', "Home\LoginController@register");
Route::post('/toregister', "Home\LoginController@toregister");
Route::get('/aa', "Home\LoginController@aa");

Route::get("abc",function (){
    return 11111;
});