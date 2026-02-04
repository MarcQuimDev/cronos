<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 sm:px-0 mb-6 animate-fade-in-up">
        <div class="bg-neutral-900 shadow-xl rounded-lg p-6 border border-neutral-800 hover:border-purple-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="flex items-center mb-4">
                <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Evolució del TVOC
                </h2>
            </div>
            @if($selectedDate && $chartData->count() > 0)
            <div class="relative h-80"><canvas id="tvocChart"></canvas></div>
            @elseif(!$selectedDate)
            <div class="flex items-center justify-center h-40 text-neutral-500"><p>Selecciona una data per veure la gràfica</p></div>
            @else
            <div class="flex items-center justify-center h-40 text-neutral-500"><p>No hi ha dades per aquesta data</p></div>
            @endif
        </div>
    </div>

    <style>
    @keyframes fade-in-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fade-in-up 0.6s ease-out; }
    </style>

    <div class="px-4 sm:px-0">
        <div class="bg-neutral-900 shadow-xl rounded-lg overflow-hidden border border-neutral-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-800">
                    <thead class="bg-black/30">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">TVOC (ppb)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Ubicació</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody class="bg-neutral-900 divide-y divide-neutral-800">
                        @forelse($tvocData as $data)
                        <tr class="hover:bg-neutral-800 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($data->tvoc < 220) bg-emerald-900/50 text-emerald-200 border border-emerald-700/50
                                    @elseif($data->tvoc >= 220 && $data->tvoc < 660) bg-amber-900/50 text-amber-200 border border-amber-700/50
                                    @else bg-red-900/50 text-red-200 border border-red-700/50
                                    @endif">
                                    {{ number_format($data->tvoc, 0) }} ppb
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-300">{{ $data->location ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-300">{{ $data->timestamp ? date('d/m/Y H:i:s', strtotime($data->timestamp)) : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-neutral-500">No hi ha dades de TVOC disponibles</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($tvocData->hasPages())
    <div class="px-4 sm:px-0 mt-6"><div class="bg-neutral-900 shadow-xl rounded-lg p-4 border border-neutral-800">{{ $tvocData->links() }}</div></div>
    @endif
</main>

@if($selectedDate && $chartData->count() > 0)
<script>
(function() {
    const chartData = @json($chartData);
    const labels = chartData.map(item => { const p = item.timestamp.split(/[- :T]/); return p[3].padStart(2,'0') + ':' + p[4].padStart(2,'0'); });
    const values = chartData.map(item => parseFloat(item.tvoc));
    const ctx = document.getElementById('tvocChart');
    if (!ctx) return;
    if (window.tvocChartInstance) { window.tvocChartInstance.destroy(); window.tvocChartInstance = null; }
    window.tvocChartInstance = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label: 'TVOC (ppb)', data: values, borderColor: 'rgb(192,132,252)', backgroundColor: 'rgba(192,132,252,0.1)', tension: 0.4, fill: true, pointBackgroundColor: 'rgb(192,132,252)', pointBorderColor: 'rgb(192,132,252)', pointHoverBackgroundColor: 'rgb(255,255,255)', pointHoverBorderColor: 'rgb(192,132,252)', pointRadius: values.length > 100 ? 1 : 4, pointHoverRadius: 7, borderWidth: values.length > 100 ? 1.5 : 3 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            animation: { duration: 2000, easing: 'easeInOutQuart', delay: (ctx) => ctx.type === 'data' && ctx.mode === 'default' ? ctx.dataIndex * (1000/Math.max(values.length,1)) : 0 },
            plugins: { legend: { display: true, labels: { color: 'rgb(212,212,212)', font: { size: 14, weight: 'bold' } } }, tooltip: { backgroundColor: 'rgba(23,23,23,0.95)', titleColor: 'rgb(192,132,252)', bodyColor: 'rgb(212,212,212)', borderColor: 'rgb(192,132,252)', borderWidth: 2, padding: 12, displayColors: false, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 }, callbacks: { label: (ctx) => 'TVOC: ' + ctx.parsed.y.toFixed(0) + ' ppb' } } },
            scales: { y: { min: 0, max: 6500, ticks: { color: 'rgb(163,163,163)', font: { size: 12 }, callback: (v) => v + ' ppb', stepSize: 200 }, grid: { color: 'rgba(64,64,64,0.3)', drawBorder: false } }, x: { ticks: { color: 'rgb(163,163,163)', font: { size: 11 }, maxRotation: 45, minRotation: 45, autoSkip: true, maxTicksLimit: 24 }, grid: { color: 'rgba(64,64,64,0.2)', drawBorder: false } } },
            interaction: { intersect: false, mode: 'index' }
        }
    });
})();
</script>
@endif
