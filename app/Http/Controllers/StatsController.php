<?php

namespace App\Http\Controllers;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Laravel\Pulse\Facades\Pulse;

class StatsController extends Controller
{
    public function helloFpm()
    {
        return $this->getStatsForRoute('/hello-fpm');
    }

    public function usersFpm()
    {
        return $this->getStatsForRoute('/users-fpm');
    }

    public function usersOctane()
    {
        return $this->getStatsForRoute('/users-octane');
    }

    public function helloOctane()
    {
        return $this->getStatsForRoute('/hello-octane');
    }

    protected function getStatsForRoute(string $path)
    {
        $engine = str_contains($path, 'octane') ? 'octane' : 'fpm';

        // Route-specific metrics (App Level)
        $durationStats = Pulse::aggregate('request_duration', ['avg', 'max', 'min', 'count'], CarbonInterval::day())
            ->firstWhere('key', $path);

        $memoryStats = Pulse::aggregate('request_memory', ['avg', 'max', 'min'], CarbonInterval::day())
            ->firstWhere('key', $path);

        $failedStats = Pulse::aggregate('request_failed', 'count', CarbonInterval::day())
            ->firstWhere('key', $path);

        // Engine-specific Metrics (Infrastructure Level)
        $connectionErrors = Pulse::aggregate('system_connection_errors', 'max', CarbonInterval::hour())
            ->firstWhere('key', $engine);

        $systemTotalRequests = Pulse::aggregate('system_total_requests', 'max', CarbonInterval::hour())
            ->firstWhere('key', $engine);

        $processMemory = Pulse::aggregate("{$engine}_process_memory", 'max', CarbonInterval::hour())
            ->first();

        $systemLoad = Pulse::aggregate('system_load', 'avg', CarbonInterval::hour())
            ->first();

        $data = [
            'route' => $path,
            'engine' => strtoupper($engine),
            'total_requests' => $durationStats?->count ?? 0,
            'failed_requests' => $failedStats?->count ?? 0,
            'duration_ms' => [
                'avg' => round($durationStats?->avg ?? 0, 2),
                'max' => $durationStats?->max ?? 0,
                'min' => $durationStats?->min ?? 0,
            ],
            'memory_mb' => [
                'avg' => round($memoryStats?->avg ?? 0, 2),
                'max' => $memoryStats?->max ?? 0,
                'min' => $memoryStats?->min ?? 0,
            ],
            'system' => [
                'connection_errors' => $connectionErrors?->max ?? 0,
                'total_requests' => $systemTotalRequests?->max ?? 0,
                'process_memory' => $processMemory?->max ?? 0,
                'load_pct' => ($systemLoad?->avg ?? 0) / 100,
            ],
        ];

        return view('stats.dashboard', $data);
    }

    public function report(Request $request)
    {
        $connectionErrors = $request->input('connection_errors', 0);
        $totalRequests = $request->input('total_requests', 0);
        $engine = $request->input('engine', 'unknown');

        // Record failures specifically for this engine
        Pulse::record('system_connection_errors', $engine, (int) $connectionErrors)->max()->count();
        Pulse::record('system_total_requests', $engine, (int) $totalRequests)->max()->count();

        // Capture current OS stats immediately
        $fpmMemory = (float) shell_exec("ps aux | grep 'php-fpm' | grep -v grep | awk '{sum += $6} END {print sum / 1024}'");
        $octaneMemory = (float) shell_exec("ps aux | grep 'frankenphp' | grep -v grep | awk '{sum += $6} END {print sum / 1024}'");
        $load = (int) (sys_getloadavg()[0] * 100);

        $engine === 'fpm' ?
            Pulse::record('fpm_process_memory', 'k6_report', (int) $fpmMemory)->max()
            : Pulse::record('octane_process_memory', 'k6_report', (int) $octaneMemory)->max();

        Pulse::record('system_load', 'k6_report', $load)->avg();

        return response()->json(['status' => 'metrics_stored', 'engine' => $engine]);
    }
}
