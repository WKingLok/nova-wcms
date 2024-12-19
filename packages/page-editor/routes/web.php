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

Route::namespace('Packages\PageEditor\Http\Controllers')
    ->group(function () {
        Route::get(config('nova.path') . "/resources/{resource}/{resourceId}/editor", 'NovaPageController@__invoke')
            ->name('nova.page-editor.editor');
        Route::get(config('nova.path') . "/resources/{resource}/{resourceId}/share-widget-editor", 'NovaShareWidgetController@__invoke')
            ->name('nova.page-editor.share-widgets.editor');

        Route::middleware([HandleInertiaRequests::class])->match(['get', 'post'], config('nova.path') . '/resources/page-editors/preview', 'NovaPreviewController@__invoke')
            ->name('nova.page-editor.preview');

        Route::post('/nova-api/page-editors/{id}/editor', 'NovaPageController@update');
        Route::post('/nova-api/page-editors/{id}/share-widget-editor', 'NovaShareWidgetController@update');

        //media library upload
        Route::post("/nova-api/page-editors/media/uploads", 'MediaLibraryUploadController')
            ->middleware(['throttle:medialibrary-pro-uploads']);

        // widget callback
        Route::post('/nova-api/page-editors/widget-callback', 'NovaShareWidgetController@widgetCallback');
    });
