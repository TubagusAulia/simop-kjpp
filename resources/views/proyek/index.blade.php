<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Proyek</h1>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('proyek.create') }}" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Proyek Baru
                    </a>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($proyeks as $proyek)
                    <a href="{{ route('proyek.show', $proyek) }}" class="block group">
                        <div class="bg-white p-6 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition cursor-pointer border border-gray-50 group-hover:border-[#82C17D]/30">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#82C17D] transition">{{ $proyek->nama_proyek }}</h3>
                                    <p class="text-sm text-gray-500">Dibuat oleh: {{ $proyek->creator?->name ?? '-' }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($proyek->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($proyek->status === 'aktif') bg-blue-100 text-blue-700
                                    @elseif($proyek->status === 'selesai') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ $proyek->status === 'aktif' ? 'Aktif' : $proyek->status }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $proyek->deskripsi ?? '-' }}</p>
                            <div class="flex gap-6 text-sm text-gray-500">
                                <span>📅 {{ $proyek->start_date->format('d M Y') }} - {{ $proyek->due_date->format('d M Y') }}</span>
                                <span>👥 {{ $proyek->users->count() }} peserta</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] text-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada proyek</p>
                        <p class="text-sm mt-1">Anda belum dialokasikan ke proyek manapun.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
