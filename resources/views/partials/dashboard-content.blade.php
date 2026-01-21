<style>
@keyframes fade-slide-in {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes pulse-border {
    0%, 100% {
        border-color: rgba(34, 211, 238, 0.3);
        box-shadow: 0 0 0 0 rgba(34, 211, 238, 0);
    }
    50% {
        border-color: rgba(34, 211, 238, 0.6);
        box-shadow: 0 0 20px 0 rgba(34, 211, 238, 0.3);
    }
}

@keyframes icon-float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
}

.stat-card {
    animation: fade-slide-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
.stat-card:nth-child(5) { animation-delay: 0.5s; }
.stat-card:nth-child(6) { animation-delay: 0.6s; }

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
}

.stat-card:hover .stat-icon {
    animation: icon-float 2s ease-in-out infinite;
}

.stat-card-glow {
    position: relative;
    overflow: hidden;
}

.stat-card-glow::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(34, 211, 238, 0.1), transparent);
    transition: left 0.5s;
}

.stat-card-glow:hover::before {
    left: 100%;
}
</style>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
        <!-- Temperature Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-cyan-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-cyan-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 4 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">Temperatura</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">{{ $temperatureData ? number_format($temperatureData->temperatura, 1) : 'N/A' }}°C</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Humidity Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-blue-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">Humitat</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">{{ $humidityData ? number_format($humidityData->humitat, 1) : 'N/A' }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pressure Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-violet-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-violet-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">Pressió</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-violet-400 to-purple-400 bg-clip-text text-transparent">{{ $pressureData ? number_format($pressureData->pressio, 0) : 'N/A' }}</dd>
                            <dd class="text-sm text-neutral-400">hPa</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brightness Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-yellow-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">Brillantor</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">{{ $brightnessData ? number_format($brightnessData->brillantor, 1) : 'N/A' }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- CO2 Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-emerald-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">CO2</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-emerald-400 to-green-400 bg-clip-text text-transparent">{{ $co2Data ? number_format($co2Data->eco2, 0) : 'N/A' }}</dd>
                            <dd class="text-sm text-neutral-400">ppm</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- TVOC Card -->
        <div class="stat-card stat-card-glow bg-gradient-to-br from-neutral-900 to-neutral-800 overflow-hidden shadow-xl rounded-xl border border-neutral-700 hover:border-purple-400/60 transition-all duration-300">
            <div class="p-6 relative">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500/20 p-3 rounded-xl">
                        <svg class="stat-icon h-10 w-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-semibold text-neutral-400 truncate uppercase tracking-wider">TVOC</dt>
                            <dd class="text-5xl font-bold text-white mt-1 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">{{ $tvocData ? number_format($tvocData->tvoc, 0) : 'N/A' }}</dd>
                            <dd class="text-sm text-neutral-400">ppb</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
