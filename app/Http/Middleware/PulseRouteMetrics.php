<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Pulse\Facades\Pulse;
use Symfony\Component\HttpFoundation\Response;

class PulseRouteMetrics
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = (int) ((microtime(true) - $start) * 1000);
        $memory = (int) (memory_get_peak_usage(true) / 1024 / 1024); // MB

        $path = $request->getPathInfo();
        $targetPaths = ['/hello-fpm', '/users-fpm', '/users-octane', '/hello-octane'];

        if (in_array($path, $targetPaths)) {
            Pulse::record('request_duration', $path, $duration)->avg()->max()->min()->count();
            Pulse::record('request_memory', $path, $memory)->avg()->max()->min();

            if ($response->getStatusCode() >= 400) {
                Pulse::record('request_failed', $path)->count();
            }
        }

        return $response;
    }
}
