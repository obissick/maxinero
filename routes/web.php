<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaxscaleController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('/settings', SettingController::class);
Route::put('/settings/{setting}/select', [SettingController::class, 'select'])->name('settings.select');

Route::resource('/servers', ServerController::class);
Route::put('/servers/{server}/changestate', [ServerController::class, 'change_state'])->name('servers.state');

Route::resource('/services', ServiceController::class);
Route::delete('/services/{service}/deletelistener', [ServiceController::class, 'destroy_listener'])->name('services.delete_listener');
Route::post('/services/{service}/createlistener', [ServiceController::class, 'create_listener'])->name('services.create_listener');
Route::put('/services/{service}/changestate', [ServiceController::class, 'change_state'])->name('services.state');
Route::put('/monitors/{monitor}/changestate', [MonitorController::class, 'change_state'])->name('monitors.state');

Route::resource('/monitors', MonitorController::class);

Route::resource('/maxscale', MaxscaleController::class);
Route::post('/maxscale/flushlog', [MaxscaleController::class, 'flush_log'])->name('maxscale.flush_log');

Route::resource('/users', UserController::class);
Route::resource('/profile', ProfileController::class);