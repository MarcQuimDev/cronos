<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Chart Section -->
    <div class="px-4 sm:px-0 mb-6">
        <div class="bg-neutral-900 shadow-xl rounded-lg p-6 border border-neutral-800">
            <h2 class="text-xl font-semibold text-white mb-4">Evolució de la Pressió</h2>
            <div class="relative h-80">
                <canvas id="pressureChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="px-4 sm:px-0">
        <div class="bg-neutral-900 shadow-xl rounded-lg overflow-hidden border border-neutral-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-800">
                    <thead class="bg-black/30">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Topic
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Tipus de Sensor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">
                                Presió (hPa)
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
                        @forelse($pressureData as $data)
                        <tr class="hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-200">
                                {{ $data->topic }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-300">
                                {{ $data->sensor_type ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($data->pressio < 1000) bg-blue-900/50 text-blue-200 border border-blue-700/50
                                    @elseif($data->pressio >= 1000 && $data->pressio < 1020) bg-emerald-900/50 text-emerald-200 border border-emerald-700/50
                                    @else bg-violet-900/50 text-violet-200 border border-violet-700/50
                                    @endif">
                                    {{ number_format($data->pressio, 2) }} hPa
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
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-neutral-500">
                                No hi ha dades de pressió disponibles
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($pressureData->hasPages())
    <div class="px-4 sm:px-0 mt-6">
        <div class="bg-neutral-900 shadow-xl rounded-lg p-4 border border-neutral-800">
            {{ $pressureData->links() }}
        </div>
    </div>
    @endif
</main>

<script>
(function() {
    // Prepare data for chart (use current page data, reversed for chronological order)
    const pressureData = @json($pressureData->items());

    // Reverse data for chronological order (oldest to newest)
    const reversedData = [...pressureData].reverse();

    const labels = reversedData.map(item => {
        const date = new Date(item.timestamp);
        return date.toLocaleString('ca-ES', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    });

    const pressures = reversedData.map(item => parseFloat(item.pressio));

    // Get or create canvas
    const ctx = document.getElementById('pressureChart');
    if (!ctx) return;

    // Destroy existing chart if it exists (prevents memory leaks)
    if (window.pressureChartInstance) {
        window.pressureChartInstance.destroy();
        window.pressureChartInstance = null;
    }

    // Create new chart
    window.pressureChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pressió (hPa)',
                data: pressures,
                borderColor: 'rgb(167, 139, 250)',
                backgroundColor: 'rgba(167, 139, 250, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(167, 139, 250)',
                pointBorderColor: 'rgb(167, 139, 250)',
                pointHoverBackgroundColor: 'rgb(255, 255, 255)',
                pointHoverBorderColor: 'rgb(167, 139, 250)',
                pointRadius: 3,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'rgb(212, 212, 212)',
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(23, 23, 23, 0.9)',
                    titleColor: 'rgb(167, 139, 250)',
                    bodyColor: 'rgb(212, 212, 212)',
                    borderColor: 'rgb(167, 139, 250)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Pressió: ' + context.parsed.y.toFixed(2) + ' hPa';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        color: 'rgb(163, 163, 163)',
                        callback: function(value) {
                            return value + ' hPa';
                        }
                    },
                    grid: {
                        color: 'rgba(64, 64, 64, 0.3)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgb(163, 163, 163)',
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        color: 'rgba(64, 64, 64, 0.3)'
                    }
                }
            }
        }
    });
})();
</script>
