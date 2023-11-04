<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Development
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader(config('app.dev_hash'))) {
            return response('Error', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
