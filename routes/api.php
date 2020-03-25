<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('login/check', "LoginController@LoginCheck");
	Route::post('logout', "LoginController@logout");

    //user
    Route::get('user', "UserController@index"); 
    Route::get('user/{limit}/{offset}', "UserController@getAll");
    Route::put('user/{id}', "UserController@update");
	Route::delete('user/{id}', "UserController@delete");

    //barang
    Route::get('barang', "BarangController@index");
	Route::get('barang/{limit}/{offset}', "BarangController@getAll");
    Route::post('barang', 'BarangController@store');
    Route::put('barang/{id}', "BarangController@update");
    Route::delete('barang/{id}', 'BarangController@destroy');
    

});