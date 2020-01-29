<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateCheckIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // TODO: Validate the access token in the request headers.
        $valid = true;

        if (!$valid) {
            return abort(403, 'Invalid check-in access token');
        }

        return $next($request);
    }
}
