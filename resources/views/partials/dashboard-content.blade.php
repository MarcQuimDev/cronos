<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
        <!-- Temperature Card -->
        <div class="bg-neutral-900 overflow-hidden shadow-xl rounded-lg border border-neutral-800 hover:border-cyan-500/50 transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - Temperatura</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $temperatureData ? number_format($temperatureData->temperatura, 2) : 'N/A' }}°C</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Humidity Card -->
        <div class="bg-neutral-900 overflow-hidden shadow-xl rounded-lg border border-neutral-800 hover:border-cyan-500/50 transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - Humitat</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $humidityData ? number_format($humidityData->humitat, 2) : 'N/A' }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pressure Card -->
        <div class="bg-neutral-900 overflow-hidden shadow-xl rounded-lg border border-neutral-800 hover:border-cyan-500/50 transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - Pressió</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $pressureData ? number_format($pressureData->pressio, 2) : 'N/A' }} hPa</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
