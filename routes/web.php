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
    return 'Nothing to see here';
});

// Example rendering of PDF as a html view
Route::get('pdf-example', function() {
    return view('pdfs.export', [
        'datestamp' => \Carbon\Carbon::now()->toDateString(),
        'test' => 'Hello World',
    ]);
});

// How to test an email.
// Route::get('mailable', function () {
//     $user = \App\Models\User::find(39);
//     return new \App\Mail\SubscriptionPaymentSuccessful($user, $user->subscriptions()->first(), 0, '');
// });
