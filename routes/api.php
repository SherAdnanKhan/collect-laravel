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
    // We'll use the 'api' guard for multi-part uploads as they'll auth
    // using the JWT we have to authenticate the user through the GraphQL
    // portion of the service.
    Route::prefix('multipart-uploads')->middleware('auth:api')->group(function () {
        Route::post('/create', 'MultipartUploads@create');
        Route::post('/prepare', 'MultipartUploads@prepare');
        Route::post('/list', 'MultipartUploads@list');
        Route::post('/abort', 'MultipartUploads@abort');
        Route::post('/complete', 'MultipartUploads@complete');
    });


    // Any routes defined in this group adhere to basic token authentication
    // for the api routing, using the 'token' guard.
    Route::middleware('auth:token')->group(function() {

    });
});
