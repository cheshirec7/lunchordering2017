<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ViewBackend
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //            || Gate::allows('view-backend')))
        if (!(Gate::allows('manage-backend')))
            throw new AuthorizationException();

        return $next($request);
    }
}
