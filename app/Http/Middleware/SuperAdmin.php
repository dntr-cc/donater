<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $authenticatable = auth()->user();
        if ($authenticatable instanceof User && !$authenticatable->isSuperAdmin()) {
            return response('Error', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
