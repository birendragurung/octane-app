<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Statistics - {{ $route }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-100 min-h-screen p-4 md:p-8 selection:bg-emerald-500/30">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <header class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold tracking-wider uppercase border border-emerald-500/20">Live Monitor</span>
                    <span class="text-slate-500 text-xs font-medium">Updated just now</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight bg-gradient-to-r from-blue-400 via-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                    Performance Insight
                </h1>
                <p class="text-slate-400 mt-3 text-lg flex items-center gap-2">
                    Endpoint: <span class="text-emerald-400 font-mono bg-emerald-500/5 px-2 py-0.5 rounded border border-emerald-500/10">{{ $route }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="/stats/hello-fpm" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $route === '/hello-fpm' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5' }}">Hello FPM</a>
                <a href="/stats/users-fpm" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $route === '/users-fpm' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5' }}">Users FPM</a>
                <a href="/stats/users-octane" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $route === '/users-octane' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5' }}">Users Octane</a>
                <a href="/stats/hello-octane" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $route === '/hello-octane' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5' }}">Hello Octane</a>
            </div>
        </header>

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total Requests -->
            <div class="glass rounded-3xl p-6 transition-all hover:scale-[1.02] duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-500/10 rounded-2xl group-hover:bg-blue-500/20 transition-colors">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Throughput</div>
                </div>
                <h3 class="text-slate-400 text-sm font-semibold mb-1">Total Requests</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold tracking-tight">{{ number_format($total_requests) }}</p>
                    <span class="text-blue-500 text-xs font-bold">reqs</span>
                </div>
            </div>

            <!-- Failed Requests -->
            <div class="glass rounded-3xl p-6 transition-all hover:scale-[1.02] duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-rose-500/10 rounded-2xl group-hover:bg-rose-500/20 transition-colors">
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Errors</div>
                </div>
                <h3 class="text-slate-400 text-sm font-semibold mb-1">Failed Requests</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold tracking-tight {{ $failed_requests > 0 ? 'text-rose-400' : '' }}">{{ number_format($failed_requests) }}</p>
                    <span class="text-rose-500 text-xs font-bold">fails</span>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="glass rounded-3xl p-6 transition-all hover:scale-[1.02] duration-300 group border-l-4 border-l-emerald-500/40">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/10 rounded-2xl group-hover:bg-emerald-500/20 transition-colors">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reliability</div>
                </div>
                <h3 class="text-slate-400 text-sm font-semibold mb-1">Success Rate</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold tracking-tight text-emerald-400">
                        {{ $total_requests > 0 ? round((($total_requests - $failed_requests) / $total_requests) * 100, 1) : 100 }}%
                    </p>
                </div>
            </div>

            <!-- Avg Duration -->
            <div class="glass rounded-3xl p-6 transition-all hover:scale-[1.02] duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-amber-500/10 rounded-2xl group-hover:bg-amber-500/20 transition-colors">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Latency</div>
                </div>
                <h3 class="text-slate-400 text-sm font-semibold mb-1">Avg Duration</h3>
                <div class="flex items-baseline gap-2">
                    <p class="text-4xl font-bold tracking-tight text-amber-400">{{ $duration_ms['avg'] }}</p>
                    <span class="text-amber-500 text-xs font-bold">ms</span>
                </div>
            </div>
        </div>

        <!-- Secondary Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- System Saturation (New) -->
            <div class="glass rounded-[2rem] p-8 relative overflow-hidden border-t-4 border-t-rose-500/40">
                <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    System Saturation
                </h2>
                <div class="space-y-6">
                    <div class="p-4 bg-blue-500/5 rounded-2xl border border-blue-500/10">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">k6 Total Requests</span>
                            <span class="text-2xl font-bold text-blue-400">{{ number_format($system['total_requests']) }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-widest">Load test attempts</p>
                    </div>
                    <div class="p-4 bg-rose-500/5 rounded-2xl border border-rose-500/10">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">{{ strtoupper($engine) }} Connect Errors</span>
                            <span class="text-2xl font-bold {{ $system['connection_errors'] > 0 ? 'text-rose-400' : 'text-slate-500' }}">{{ number_format($system['connection_errors']) }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-widest">Network-level drops (k6 report)</p>
                    </div>
                    <div class="p-4 bg-purple-500/5 rounded-2xl border border-purple-500/10">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">{{ $engine }} Process Memory</span>
                            <span class="text-2xl font-bold text-purple-400">{{ number_format($system['process_memory']) }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-widest">Combined RSS usage in MB</p>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-400">System Load Avg</span>
                            <span class="text-indigo-400 font-bold">{{ $system['load_pct'] }}</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-indigo-500 h-full transition-all duration-1000" style="width: {{ min($system['load_pct'] * 10, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latency Profile -->
            <div class="glass rounded-[2rem] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-24 h-24 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Latency Profile
                </h2>
                <div class="space-y-8">
                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <div class="flex flex-col">
                                <span class="text-slate-300 font-semibold">Average Response</span>
                                <span class="text-slate-500 text-xs uppercase tracking-tighter">Mean time to first byte</span>
                            </div>
                            <span class="text-2xl font-mono font-bold text-amber-400">{{ $duration_ms['avg'] }}<small class="text-xs ml-1 text-slate-500">ms</small></span>
                        </div>
                        <div class="w-full bg-slate-800/50 rounded-full h-3 p-0.5 overflow-hidden border border-slate-700/50">
                            <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-full rounded-full transition-all duration-1000" style="width: {{ min(($duration_ms['avg'] / 500) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <div class="text-slate-500 text-[10px] font-bold uppercase mb-1 tracking-widest">Max Peak</div>
                            <div class="text-2xl font-bold text-rose-400">{{ $duration_ms['max'] }}<span class="text-xs text-slate-600 font-normal ml-1">ms</span></div>
                        </div>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <div class="text-slate-500 text-[10px] font-bold uppercase mb-1 tracking-widest">Best Case</div>
                            <div class="text-2xl font-bold text-emerald-400">{{ $duration_ms['min'] }}<span class="text-xs text-slate-600 font-normal ml-1">ms</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memory Consumption -->
            <div class="glass rounded-[2rem] p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-24 h-24 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Memory Footprint
                </h2>
                
                <div class="flex items-center justify-center py-6">
                    <div class="relative">
                        <svg class="w-48 h-48 transform -rotate-90">
                            <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-800" />
                            <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" 
                                stroke-dasharray="502.6" 
                                stroke-dashoffset="{{ 502.6 - (502.6 * min($memory_mb['avg'] / 128, 1)) }}" 
                                class="text-purple-500 transition-all duration-1000 ease-out" 
                                stroke-linecap="round" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-black text-white">{{ $memory_mb['avg'] }}</span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Avg MB</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="text-center p-3 bg-white/5 rounded-xl border border-white/5">
                        <span class="text-[9px] font-bold text-slate-500 uppercase block mb-1">Max Recorded</span>
                        <span class="text-lg font-bold text-purple-400">{{ $memory_mb['max'] }}<small class="text-[10px] ml-0.5 opacity-50">MB</small></span>
                    </div>
                    <div class="text-center p-3 bg-white/5 rounded-xl border border-white/5">
                        <span class="text-[9px] font-bold text-slate-500 uppercase block mb-1">Min Baseline</span>
                        <span class="text-lg font-bold text-purple-400">{{ $memory_mb['min'] }}<small class="text-[10px] ml-0.5 opacity-50">MB</small></span>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-16 pb-8 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-600 text-sm">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> PHP 8.3</span>
                <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Octane Ready</span>
            </div>
            <p>© 2026 Engine Metrics · Performance Monitoring Dashboard</p>
        </footer>
    </div>
</body>
</html>
