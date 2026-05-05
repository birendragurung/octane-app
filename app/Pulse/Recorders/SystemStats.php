<?php

namespace App\Pulse\Recorders;

use Laravel\Pulse\Events\SharedBeat;
use Laravel\Pulse\Facades\Pulse;

class SystemStats
{
    public string $listen = SharedBeat::class;

    public function record(SharedBeat $event): void
    {
        // 1. Capture PHP-FPM Memory (Sum of all FPM worker RSS in MB)
        $fpmMemory = (float) shell_exec("ps aux | grep 'php-fpm' | grep -v grep | awk '{sum += $6} END {print sum / 1024}'");

        // 2. Capture FrankenPHP/Octane Memory (Sum of FrankenPHP RSS in MB)
        $octaneMemory = (float) shell_exec("ps aux | grep 'frankenphp' | grep -v grep | awk '{sum += $6} END {print sum / 1024}'");

        // 3. System Load
        $load = (int) (sys_getloadavg()[0] * 100);

        // Record metrics with specific keys for the dashboard
        Pulse::record('fpm_process_memory', 'server', (int) $fpmMemory, $event->time)->avg()->max();
        Pulse::record('octane_process_memory', 'server', (int) $octaneMemory, $event->time)->avg()->max();
        Pulse::record('system_load', 'server', $load, $event->time)->avg()->max();
    }
}
