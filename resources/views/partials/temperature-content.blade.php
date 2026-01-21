<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Chart Section -->
    <div class="px-4 sm:px-0 mb-6 animate-fade-in-up">
        <div class="bg-neutral-900 shadow-xl rounded-lg p-6 border border-neutral-800 hover:border-cyan-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-500/10">
            <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11a4 4 0 104 4V6a2 2 0 10-4 0v5zm0 0a2 2 0 110 4m3-10h.01M12 2v1m0 3V5" />
                </svg>
                Evolució de la Temperatura
            </h2>
            <div class="relative h-80">
                <canvas id="temperatureChart"></canvas>
            </div>
        </div>
    </div>

    <style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out;
    }
    </style>

    <!-- Data Table -->
    <div class="px-4 sm:px-0">
        <div class="bg-neutral-900 shadow-xl rounded-lg overflow-hidden border border-neutral-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-800">
                    <thead class="bg-black/30">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Temperatura (°C)
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Ubicació
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Data/Hora
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-neutral-900 divide-y divide-neutral-800">
                        @forelse($temperatureData as $data)
                        <tr class="hover:bg-neutral-800 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($data->temperatura < 20) bg-blue-900/50 text-blue-200 border border-blue-700/50
                                    @elseif($data->temperatura >= 20 && $data->temperatura < 30) bg-emerald-900/50 text-emerald-200 border border-emerald-700/50
                                    @else bg-red-900/50 text-red-200 border border-red-700/50
                                    @endif">
                                    {{ number_format($data->temperatura, 2) }}°C
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-300">
                                {{ $data->location ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-300">
                                {{ $data->timestamp ? date('d/m/Y H:i:s', strtotime($data->timestamp)) : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm text-neutral-500">
                                No hi ha dades de temperatura disponibles
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($temperatureData->hasPages())
    <div class="px-4 sm:px-0 mt-6">
        <div class="bg-neutral-900 shadow-xl rounded-lg p-4 border border-neutral-800">
            {{ $temperatureData->links() }}
        </div>
    </div>
    @endif
</main>

<script>
(function() {
    // Prepare data for chart (use current page data, reversed for chronological order)
    const temperatureData = @json($temperatureData->items());

    // Reverse data for chronological order (oldest to newest)
    const reversedData = [...temperatureData].reverse();

    const labels = reversedData.map(item => {
        const date = new Date(item.timestamp);
        return date.toLocaleString('ca-ES', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    });

    const temperatures = reversedData.map(item => parseFloat(item.temperatura));

    // Get or create canvas
    const ctx = document.getElementById('temperatureChart');
    if (!ctx) return;

    // Destroy existing chart if it exists (prevents memory leaks)
    if (window.temperatureChartInstance) {
        window.temperatureChartInstance.destroy();
        window.temperatureChartInstance = null;
    }

    // Create new chart with animations
    window.temperatureChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Temperatura (°C)',
                data: temperatures,
                borderColor: 'rgb(34, 211, 238)',
                backgroundColor: 'rgba(34, 211, 238, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(34, 211, 238)',
                pointBorderColor: 'rgb(34, 211, 238)',
                pointHoverBackgroundColor: 'rgb(255, 255, 255)',
                pointHoverBorderColor: 'rgb(34, 211, 238)',
                pointRadius: 4,
                pointHoverRadius: 7,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default') {
                        delay = context.dataIndex * 50;
                    }
                    return delay;
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'rgb(212, 212, 212)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(23, 23, 23, 0.95)',
                    titleColor: 'rgb(34, 211, 238)',
                    bodyColor: 'rgb(212, 212, 212)',
                    borderColor: 'rgb(34, 211, 238)',
                    borderWidth: 2,
                    padding: 12,
                    displayColors: false,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Temperatura: ' + context.parsed.y.toFixed(2) + '°C';
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 35,
                    ticks: {
                        color: 'rgb(163, 163, 163)',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return value + '°C';
                        },
                        stepSize: 5
                    },
                    grid: {
                        color: 'rgba(64, 64, 64, 0.3)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: 'rgb(163, 163, 163)',
                        font: {
                            size: 11
                        },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        color: 'rgba(64, 64, 64, 0.2)',
                        drawBorder: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
})();
</script>
