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

// We'll use the 'api' guard for multi-part uploads as they'll auth
// using the JWT we have to authenticate the user through the GraphQL
// portion of the service.
Route::prefix('multipart-uploads')->middleware('auth:api')->group(function () {
    Route::post('/create', '\App\Http\Controllers\MultipartUploadsController@create');
    Route::post('/prepare', '\App\Http\Controllers\MultipartUploadsController@prepare');
    Route::post('/list', '\App\Http\Controllers\MultipartUploadsController@list');
    Route::post('/abort', '\App\Http\Controllers\MultipartUploadsController@abort');
    Route::post('/complete', '\App\Http\Controllers\MultipartUploadsController@complete');
});

Route::post('project-thumbnail-upload', '\App\Http\Controllers\ProjectThumbnailUpload@handle')->middleware('auth:api');

// The RIN import/export routes.
Route::prefix('rin')->middleware('auth:api')->group(function () {
    // Route::any('/import', '\App\Http\Controllers\RINController@import');
    Route::get('/export', '\App\Http\Controllers\RINController@export');
});

// The PDF
Route::prefix('pdf')->middleware('auth:api')->group(function () {
    Route::get('/export', '\App\Http\Controllers\PDFController@export');
});

// Any routes defined in this group adhere to basic token authentication
// for the api routing, using the 'token' guard.
Route::middleware('auth:token')->group(function() {

    // The stripe webhook endpoints, it automatically
    // verifies the stripe signature to ensure the
    // requests are coming from the correct Stripe.
    Route::post(
        'stripe/webhook',
        '\App\Http\Controllers\Webhooks\StripeController@handleWebhook'
    );

    Route::post(
        'lamdba/update-file-info',
        '\App\Http\Controllers\Webhooks\LambdaController@updateFileInfo'
    );

    Route::post(
        'zipgenerator/complete',
        '\App\Http\Controllers\Webhooks\ZipGeneratorController@complete'
    );

    Route::post(
        'sharezipgenerator/complete',
        '\App\Http\Controllers\Webhooks\ShareZipGeneratorController@complete'
    );
});
