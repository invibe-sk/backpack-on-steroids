<?php

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Invibe\BackpackOnSteroids\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::prefix('file-manager')->name('filemanager.')->group(function () {
        Route::get('/', 'FileManagerController@view')->name('index');
        Route::get('/tinymce5', 'FileManagerController@tinymce5')->name('tinymce5');
    });

    Route::prefix('files')->name('files.')->group(function () {
        Route::post('/upload-single-temp-file', 'FilesController@uploadSingleFile')->name('uploadSingleFile');
        Route::get('/get-temp-files', 'FilesController@getTempFiles')->name('getTempFiles');
        Route::get('/get-media', 'FilesController@getMedia')->name('getMedia');
        Route::get('/get-temp-file/{file}', 'FilesController@getTempFile')->name('getTempFile');
    });

}); // this should be the absolute last line of this file
