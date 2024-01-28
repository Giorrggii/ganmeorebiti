<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\MySuperMiddleware as Middleware;
use Illuminate\Http\Request;
use Closure;

class MySuperMiddleware
{
    public function handle($request, Closure $next)
    {
        $name = $request->input('name');

        if ($name && $name === 'my-super-middleware') {
            return $next($request);
        }

        abort(404, 'Access denied');
    }
}