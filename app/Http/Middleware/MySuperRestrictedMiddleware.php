<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\MySuperRestrictedMiddleware as Middleware;
use Illuminate\Http\Request;
use Closure;

class MySuperRestrictedMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->route()->named('custom-route')) {
            return $next($request);
        }

        return abort(404);
    }
}