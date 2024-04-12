<?php

namespace DGTournaments\Http\Middleware;

use Closure;
use DGTournaments\Jobs\LogRequest as LogRequestJob;
use Illuminate\Support\Facades\Auth;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        LogRequestJob::dispatch([
            'ip' => $request->ip(),
            'method' => $request->method(),
            'uri' => substr($request->getRequestUri(), 0, 250)
        ], Auth::check() ? Auth::user() : null);

        return $response;
    }
}
