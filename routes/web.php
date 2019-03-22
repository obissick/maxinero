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

Route::get('/', function () {
    //return redirect('/');
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/settings', 'SettingController');
Route::put('/settings/{setting}/select', 'SettingController@select')->name('settings.select');

Route::resource('/servers', 'ServerController');

Route::resource('/services', 'ServiceController');
Route::delete('/services/{service}/deletelistener', 'ServiceController@destroy_listener')->name('services.delete_listener');
Route::post('/services/{service}/createlistener', 'ServiceController@create_listener')->name('services.create_listener');

Route::resource('/monitors', 'MonitorController');

Route::resource('/maxscale', 'MaxscaleController');

Route::resource('/users', 'UserController');