<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SessionCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        $token = $request->get('accessToken');

        $tokenKey = SessionCode::checkinCacheKey($token);

        if (!Cache::has($tokenKey)) {
            return abort(403, 'Invalid check-in access token');
        }

        return $next($request);
    }
}
