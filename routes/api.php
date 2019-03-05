<?php

use Illuminate\Http\Request;

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

Route::prefix('api')->group(function () {
    Route::prefix('multipart-uploads')->middleware('auth:api')->group(function () {
        Route::post('/create', 'MultipartUploads@create');
        Route::post('/prepare', 'MultipartUploads@prepare');
        Route::post('/list', 'MultipartUploads@list');
        Route::post('/abort', 'MultipartUploads@abort');
        Route::post('/complete', 'MultipartUploads@complete');
    });
});
