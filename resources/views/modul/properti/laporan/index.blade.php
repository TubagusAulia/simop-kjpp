<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Header + Mode Buttons -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Laporan</h1>
                <div class="flex items-center gap-2">
                    <button id="btn-proyek" onclick="switchTab('proyek')"
                        class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-[#82C17D] text-white shadow-sm">
                        Laporan Proyek
                    </button>
                    <button id="btn-tahunan" onclick="switchTab('tahunan')"
                        class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700">
                        Laporan Tahunan
                    </button>
                </div>
            </div>

            <!-- ==================== LAPORAN PROYEK ==================== -->
            <div id="tab-proyek">
                @forelse ($proyekData as $data)
                @php $proyek = $data['proyek']; @endphp
                <a href="{{ route('laporan.proyek.show', $proyek->id) }}" class="block group">
                    <div class="bg-white p-6 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] mb-4 hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition cursor-pointer border border-gray-50 group-hover:border-[#82C17D]/30">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-full bg-[#82C17D]/10 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-[#82C17D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#82C17D] transition">
                                            {{ $proyek->nama_proyek }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Client: {{ $data['client_name'] }}
                                            &bull; {{ $data['tanggal']->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                @if($data['nilai'])
                                <div class="ml-13 mt-3">
                                    <span class="text-xs text-gray-400 uppercase font-bold tracking-widest">Nilai Properti</span>
                                    <p class="text-xl font-bold text-[#82C17D]">Rp {{ number_format($data['nilai']->nilai, 0, ',', '.') }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">
                                    Selesai
                                </span>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-[#82C17D] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-medium">Belum ada laporan proyek</p>
                    <p class="text-sm mt-1">Laporan akan muncul setelah proyek memiliki penilaian.</p>
                </div>
                @endforelse
            </div>

            <!-- ==================== LAPORAN TAHUNAN ==================== -->
            <div id="tab-tahunan" class="hidden">
                @forelse ($laporanTahunan as $tahunan)
                <div class="bg-white p-6 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] mb-4 hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition border border-gray-50 group">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#82C17D]/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#82C17D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Tahun {{ $tahunan['year'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $tahunan['count'] }} proyek selesai</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('laporan.tahunan.show', $tahunan['year']) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-full text-xs font-bold transition">
                                Detail
                            </a>
                            <a href="{{ route('laporan.tahunan.pdf', $tahunan['year']) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#82C17D]/10 text-[#82C17D] hover:bg-[#82C17D]/20 rounded-full text-xs font-bold transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] text-center py-12 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-lg font-medium">Belum ada laporan tahunan</p>
                    <p class="text-sm mt-1">Laporan tahunan akan muncul setelah ada proyek yang diselesaikan.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function switchTab(tab) {
            var btnProyek = document.getElementById('btn-proyek');
            var btnTahunan = document.getElementById('btn-tahunan');
            var tabProyek = document.getElementById('tab-proyek');
            var tabTahunan = document.getElementById('tab-tahunan');

            if (tab === 'proyek') {
                btnProyek.className = 'px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-[#82C17D] text-white shadow-sm';
                btnTahunan.className = 'px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700';
                tabProyek.classList.remove('hidden');
                tabTahunan.classList.add('hidden');
            } else {
                btnProyek.className = 'px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700';
                btnTahunan.className = 'px-5 py-2 rounded-full text-sm font-bold transition-all duration-200 bg-[#82C17D] text-white shadow-sm';
                tabProyek.classList.add('hidden');
                tabTahunan.classList.remove('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
