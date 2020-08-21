<?php

namespace App\Http\GraphQL\Execution;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;

class ExtensionErrorHandler implements ErrorHandler
{
    /**
     * Handle Exceptions that implement Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions
     * and add extra content from them to the 'extensions' key of the Error that is rendered
     * to the User.
     *
     * @param Error    $error
     * @param \Closure $next
     *
     * @return array
     */
    public static function handle(Error $error, \Closure $next): array
    {
        $underlyingException = $error->getPrevious();

        if ($underlyingException instanceof \Exception) {
            report($underlyingException);
        }

        if ($underlyingException && $underlyingException instanceof \Illuminate\Auth\AuthenticationException) {
            $underlyingException = new \Nuwave\Lighthouse\Exceptions\AuthenticationException;
        }

        if ($underlyingException && $underlyingException instanceof RendersErrorsExtensions) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $error = new Error(
                $error->message,
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $underlyingException,
                $underlyingException->extensionsContent()
            );
        }

        return $next($error);
    }
}
