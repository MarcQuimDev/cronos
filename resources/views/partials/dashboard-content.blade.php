<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
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

        <!-- Brightness Card -->
        <div class="bg-neutral-900 overflow-hidden shadow-xl rounded-lg border border-neutral-800 hover:border-cyan-500/50 transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - Brillantor</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $brightnessData ? number_format($brightnessData->brillantor, 2) : 'N/A' }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- CO2 Card -->
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
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - CO2</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $co2Data ? number_format($co2Data->eco2, 0) : 'N/A' }} ppm</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- TVOC Card -->
        <div class="bg-neutral-900 overflow-hidden shadow-xl rounded-lg border border-neutral-800 hover:border-cyan-500/50 transition-all duration-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-neutral-400 truncate">Última Lectura - TVOC</dt>
                            <dd class="text-3xl font-semibold text-white">{{ $tvocData ? number_format($tvocData->tvoc, 0) : 'N/A' }} ppb</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
