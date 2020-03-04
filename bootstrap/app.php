<?php

use Illuminate\Support\Facades\App;
use Maxbanton\Cwh\Handler\CloudWatch;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Configure Cloud Watch Logs for logging
$app->configureMonologUsing(function($monolog) {
    $cwClient = App::make('aws')->createClient('CloudWatchLogs');
    $cwGroupName = env('AWS_CWL_GROUP', 'laravel-app-logs');
    $cwStreamNameApp = env('AWS_CWL_APP', 'laravel-app-name');
    $cwTagName = env('AWS_CWL_TAG_NAME', 'application');
    $cwTagValue = env('AWS_CWL_TAG_VALUE', 'laravel-testapp01');
    $cwRetentionDays = 90;
    $cwHandlerApp = new CloudWatch($cwClient, $cwGroupName, $cwStreamNameApp, $cwRetentionDays, 10000, [ $cwTagName => $cwTagValue ] );

    $monolog->pushHandler($cwHandlerApp);
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
