<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mb-8">Ringkasan proyek dan aktivitas terbaru.</p>

            <!-- Charts Row: Pie 1/4, Bar 3/4 -->
            <div class="flex gap-6 mb-8">
                <!-- Pie Chart: 1/4 width, round/compact -->
                <div class="w-1/4 bg-white p-6 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)]">
                    <h2 class="text-base font-bold text-gray-800 mb-2 text-center">Proyek Aktif</h2>
                    <div class="flex items-center justify-center" style="height: 200px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="flex flex-col gap-2 mt-4 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                            <span class="text-gray-600">Verifikasi Dokumen</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                            <span class="text-gray-600">Verifikasi Fisik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            <span class="text-gray-600">Penilaian</span>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart: 3/4 width, full height -->
                <div class="w-3/4 bg-white p-6 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)]">
                    <h2 class="text-base font-bold text-gray-800 mb-4">Proyek Selesai per {{ $currentYear }}</h2>
                    <div style="height: 200px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Project List: Current Phase, Ordered by Due Date -->
            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Proyek Berjalan (Berdasarkan Tanggal Deadline)</h2>

                @forelse ($projectsByPhase as $project)
                    <a href="{{ route('proyek.show', $project['id']) }}"
                        class="block border border-gray-100 rounded-[16px] p-5 mb-4 hover:border-[#82C17D]/30 hover:shadow-md transition group">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#82C17D] transition">
                                        {{ $project['nama_proyek'] }}
                                    </h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($project['status'] === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($project['status'] === 'berjalan') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $project['status'] }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>📅 Deadline: {{ \Carbon\Carbon::parse($project['due_date'])->format('d M Y') }}</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($project['current_phase'] === 'dokumen') bg-blue-50 text-blue-700
                                        @elseif($project['current_phase'] === 'fisik') bg-yellow-50 text-yellow-700
                                        @else bg-green-50 text-green-700
                                        @endif">
                                        @if($project['current_phase'] === 'dokumen') 📄 Verifikasi Dokumen
                                        @elseif($project['current_phase'] === 'fisik') 🏠 Verifikasi Fisik
                                        @else 📊 Penilaian
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-[#82C17D] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">Tidak ada proyek yang sedang berjalan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Pie Chart (compact, round) ---
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieData = @json($pieData);
        const hasPieData = pieData.data.some(v => v > 0);

        if (hasPieData) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieData.labels,
                    datasets: [{
                        data: pieData.data,
                        backgroundColor: ['#3b82f6', '#eab308', '#22c55e'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                    return `${ctx.label}: ${ctx.raw} proyek (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            pieCtx.canvas.parentElement.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-10 h-10 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    </svg>
                    <p class="text-xs text-center">Tidak ada<br>proyek aktif</p>
                </div>
            `;
        }

        // --- Bar Chart (full height, months without year) ---
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barData = @json($barData);

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barData.labels,
                datasets: [{
                    label: 'Proyek Selesai',
                    data: barData.data,
                    backgroundColor: '#82C17D',
                    borderRadius: 6,
                    barThickness: 18,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return `${ctx.raw} proyek selesai`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#9ca3af',
                        },
                        grid: {
                            color: '#f3f4f6',
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            maxRotation: 45,
                        },
                        grid: {
                            display: false,
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
