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

Route::namespace('Packages\Approval\Http\Controllers')
    ->group(function () {
        Route::get(config('nova.path') . "/resources/{resource}/{resourceId}/archive", 'NovaApprovalController@__invoke')->name('nova.approval.archive');

        /**
         * API routes
         */
        Route::post('/nova-api/approval/actions', 'NovaApprovalController@actions');
    });
