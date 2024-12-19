<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HandleInertiaRequests;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::namespace('Packages\HeaderMenu\Http\Controllers')
    ->group(function () {
        Route::get(config('nova.path') . "/resources/header-menus", 'NovaHeaderController@__invoke');
        Route::get(config('nova.path') . "/resources/header-menus/reorder", 'NovaHeaderMenuReorderController@__invoke');
        Route::post('/nova-api/header-menus/reorder', 'NovaHeaderMenuReorderController@update');
    });
