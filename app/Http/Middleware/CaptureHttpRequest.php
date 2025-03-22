<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureHttpRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // log all the incoming requests
        logger()->info('Incoming request', [
            'method'     => $request->method(),
            'url'        => $request->fullUrl(),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers'    => $request->headers->all(),
            'body'       => $request->all(),
            'user'       => $request->user()?->ID ?? 'Guest',
        ]);
        return $next($request);
    }
}
