<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Back link + Project title -->
            <div class="mb-6">
                <a href="{{ route('laporan.project') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar Laporan
                </a>
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $proyek->nama_proyek }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $proyek->creator?->name ?? '-' }} ({{ $proyek->creator?->username ?? '-' }})</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($proyek->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($proyek->status === 'aktif') bg-blue-100 text-blue-700
                            @elseif($proyek->status === 'selesai') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ $proyek->status === 'aktif' ? 'Aktif' : $proyek->status }}
                        </span>
                        @if($proyek->properti)
                            @php $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti); @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-[#82C17D]/10 text-[#82C17D] uppercase tracking-wider border border-[#82C17D]/20">
                                🏠 {{ $typeReqs['name'] ?? 'Tipe Tidak Diketahui' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main content: Sidebar + Content -->
            <div class="flex gap-8">
                <!-- LEFT SIDEBAR -->
                <div class="w-64 shrink-0">
                    <!-- Project info card (TOP) -->
                    <div class="bg-white rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] p-4 mb-4">
                        <h4 class="font-bold text-gray-800 text-sm mb-3">Info Proyek</h4>
                        <div class="space-y-3 text-sm">
                            @php
                                $phaseLabels = ['dimulai' => 'Proyek Mulai', 'dokumen' => 'Verifikasi Dokumen', 'fisik' => 'Verifikasi Fisik', 'dinilai' => 'Penilaian Properti', 'selesai' => 'Proyek Selesai'];
                                $currentPhaseLabel = $phaseLabels[$proyek->current_phase ?? 'dokumen'] ?? 'Proyek Mulai';
                                $task = $proyek->getCurrentTask();
                                $collectionStatus = $proyek->getCurrentCollection()?->status;
                                $phaseColor = match($proyek->current_phase ?? 'dokumen') { 'dokumen' => 'blue', 'fisik' => 'yellow', 'dinilai' => 'purple', 'selesai' => 'green', default => 'gray' };
                            @endphp
                            <div>
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest block mb-1">Status</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if(($proyek->current_phase ?? 'dokumen') === 'selesai') bg-green-100 text-green-700
                                    @elseif(($proyek->current_phase ?? 'dokumen') === 'dinilai') bg-purple-100 text-purple-700
                                    @elseif(($proyek->current_phase ?? 'dokumen') === 'fisik') bg-yellow-100 text-yellow-700
                                    @elseif(($proyek->current_phase ?? 'dokumen') === 'dokumen') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ $currentPhaseLabel }}
                                </span>
                            </div>

                            @if($proyek->current_phase !== 'selesai' && $task)
                                <div class="rounded-[16px] p-3 border
                                    @switch($phaseColor)
                                        @case('blue') bg-blue-50/50 border-blue-100 @break
                                        @case('yellow') bg-yellow-50/50 border-yellow-100 @break
                                        @case('purple') bg-purple-50/50 border-purple-100 @break
                                        @default bg-gray-50 border-gray-100
                                    @endswitch">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                                            @switch($phaseColor)
                                                @case('blue') bg-blue-100 text-blue-600 @break
                                                @case('yellow') bg-yellow-100 text-yellow-600 @break
                                                @case('purple') bg-purple-100 text-purple-600 @break
                                                @default bg-gray-100 text-gray-600
                                            @endswitch">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider
                                                @switch($phaseColor)
                                                    @case('blue') bg-blue-100 text-blue-700 @break
                                                    @case('yellow') bg-yellow-100 text-yellow-700 @break
                                                    @case('purple') bg-purple-100 text-purple-700 @break
                                                    @default bg-gray-100 text-gray-700
                                                @endswitch">
                                                {{ $task['role'] }}
                                            </span>
                                            <p class="text-gray-600 text-xs mt-1 leading-relaxed">{{ $task['message'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($collectionStatus === 'selesai')
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-100">✓ {{ $phaseLabels[$proyek->current_phase] ?? '' }} Selesai</span>
                                </div>
                                @endif
                            @endif

                            @if($proyek->current_phase === 'selesai')
                                <div class="rounded-[16px] p-3 bg-green-50/50 border border-green-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-green-700">Proyek Selesai</span>
                                    </div>
                                </div>
                            @endif

                            @if($proyek->properti?->nilai)
                            <div class="mt-2">
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest block mb-1">Nilai</span>
                                <p class="text-lg font-bold text-[#82C17D]">Rp {{ number_format($proyek->properti->nilai->nilai, 0, ',', '.') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Menu (BOTTOM) — only 2 items -->
                    <div class="bg-white rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] overflow-hidden">
                        <div class="p-4 border-b border-gray-100 text-center">
                            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-widest">Menu</h3>
                        </div>
                        <nav class="p-2">
                            <a href="{{ route('laporan.proyek.show', ['proyek' => $proyek->id, 'menu' => 'detail']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ ($activeMenu ?? 'detail') === 'detail' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">ℹ️ Detail</a>
                            <a href="{{ route('laporan.proyek.show', ['proyek' => $proyek->id, 'menu' => 'laporan']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ ($activeMenu ?? '') === 'laporan' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">📄 Laporan</a>
                        </nav>
                    </div>
                </div>

                <!-- RIGHT CONTENT AREA -->
                <div class="flex-1">
                    <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] min-h-[500px]">

                        {{-- ==================== DETAIL TAB ==================== --}}
                        @if(($activeMenu ?? 'detail') === 'detail')
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Penilaian</h2>
                            <p class="text-gray-500 mb-6">Informasi identitas dan kontrak proyek.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border border-gray-100 rounded-[30px] p-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Informasi Umum</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Nama Proyek</label>
                                            <p class="text-gray-800 font-semibold text-sm mt-0.5 truncate">{{ $proyek->nama_proyek }}</p>
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Durasi Pekerjaan</label>
                                            <p class="text-gray-800 font-semibold text-sm mt-0.5">{{ $proyek->start_date->format('d M Y') }} &mdash; {{ $proyek->due_date->format('d M Y') }}</p>
                                        </div>
                                        @if($proyek->deskripsi)
                                        <div>
                                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Deskripsi Proyek</label>
                                            <p class="text-gray-600 text-sm leading-relaxed mt-0.5">{{ $proyek->deskripsi }}</p>
                                        </div>
                                        @endif
                                        @if($proyek->kontrak_file)
                                        <div>
                                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-2">Berkas Kontrak Resmi</label>
                                            <a href="{{ asset('storage/' . $proyek->kontrak_file) }}" target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold transition hover:bg-blue-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Unduh Kontrak.pdf
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="border border-gray-100 rounded-[30px] p-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Peserta Terlibat</h3>
                                    @php
                                        $karyawans = $proyek->users->where('role', 'karyawan');
                                        $clients = $proyek->users->where('role', 'client');
                                        $mitras = $proyek->users->where('role', 'mitra');
                                    @endphp
                                    @if($karyawans->isNotEmpty())
                                        <div class="mb-4">
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Internal (Karyawan)</p>
                                            @foreach($karyawans as $u)
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-profile-avatar :user="$u" size="xl" />
                                                <div><p class="text-gray-800 font-semibold text-sm">{{ $u->name }}</p><p class="text-[10px] text-gray-400">{{ $u->username }}</p></div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($clients->isNotEmpty())
                                        <div class="mb-4">
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Eksternal (Klien)</p>
                                            @foreach($clients as $u)
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-profile-avatar :user="$u" size="xl" />
                                                <div><p class="text-gray-800 font-semibold text-sm">{{ $u->name }}</p><p class="text-[10px] text-gray-400">{{ $u->username }}</p></div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($mitras->isNotEmpty())
                                        <div>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Partner (Mitra)</p>
                                            @foreach($mitras as $u)
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-profile-avatar :user="$u" size="xl" />
                                                <div><p class="text-gray-800 font-semibold text-sm">{{ $u->name }}</p><p class="text-[10px] text-gray-400">{{ $u->username }}</p></div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Stepper -->
                            @php
                                $steps = [['label' => 'Proyek Mulai', 'key' => 'dimulai'], ['label' => 'Verifikasi Dokumen', 'key' => 'dokumen'], ['label' => 'Verifikasi Fisik', 'key' => 'fisik'], ['label' => 'Penilaian Properti', 'key' => 'dinilai'], ['label' => 'Proyek Selesai', 'key' => 'selesai']];
                                $currentPhase = $proyek->current_phase ?? 'dimulai';
                                $phaseOrder = ['dimulai', 'dokumen', 'fisik', 'dinilai', 'selesai'];
                                $currentIdx = array_search($currentPhase, $phaseOrder);
                                if ($currentIdx === false) $currentIdx = 0;
                                $isFinished = $currentPhase === 'selesai';
                            @endphp
                            <div class="mt-6 border border-gray-100 rounded-[30px] p-6">
                                <h3 class="font-bold text-gray-800 mb-5">Progress Proyek</h3>
                                <div class="flex items-start">
                                    @foreach($steps as $idx => $step)
                                        @php
                                            $isLast = $idx === count($steps) - 1;
                                            $isCompleted = $isLast ? $isFinished : $idx < $currentIdx;
                                            $isCurrent = $isLast ? false : $idx === $currentIdx;
                                            $isFuture = $isLast ? !$isFinished : $idx > $currentIdx;
                                        @endphp
                                        <div class="flex-1 flex flex-col items-center relative">
                                            @if($idx > 0)<div class="absolute top-[18px] right-1/2 w-full h-0.5 -z-10 {{ $isCompleted || $isCurrent ? 'bg-[#82C17D]' : 'bg-gray-200' }}"></div>@endif
                                            @if($idx < count($steps) - 1)<div class="absolute top-[18px] left-1/2 w-full h-0.5 -z-10 {{ $isCompleted ? 'bg-[#82C17D]' : 'bg-gray-200' }}"></div>@endif
                                            <div class="relative z-10 flex items-center justify-center w-9 h-9 rounded-full border-2 {{ $isCompleted ? 'bg-[#82C17D] border-[#82C17D]' : '' }} {{ $isCurrent ? 'bg-white border-[#82C17D] ring-4 ring-[#82C17D]/10' : '' }} {{ $isFuture ? 'bg-white border-gray-200' : '' }}">
                                                @if($isCompleted)<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @elseif($isCurrent)<span class="text-sm font-bold text-[#82C17D]">{{ $idx + 1 }}</span>
                                                @else<span class="text-xs font-medium text-gray-400">{{ $idx + 1 }}</span>@endif
                                            </div>
                                            <span class="mt-3 text-[11px] font-semibold text-center leading-tight max-w-[90px] {{ $isCompleted ? 'text-[#82C17D]' : '' }} {{ $isCurrent ? 'text-gray-800' : '' }} {{ $isFuture ? 'text-gray-400' : '' }}">{{ $step['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border border-gray-100 rounded-[30px] p-6 mt-6 bg-gray-50/30">
                                <h3 class="font-bold text-gray-800 mb-4">Dibuat Oleh</h3>
                                <div class="flex items-center gap-3">
                                    <x-profile-avatar :user="$proyek->creator" size="md" />
                                    <div><p class="text-gray-800 font-bold text-sm">{{ $proyek->creator?->name ?? '-' }}</p><p class="text-gray-400 text-xs">{{ $proyek->creator?->username ?? '-' }}</p></div>
                                </div>
                            </div>

                        {{-- ==================== LAPORAN TAB ==================== --}}
                        @elseif($activeMenu === 'laporan')
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Laporan Proyek</h2>
                                    <p class="text-gray-500 text-sm">Generate dan unduh laporan penilaian properti.</p>
                                </div>
                                <a href="{{ route('laporan.proyek.pdf', $proyek->id) }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#82C17D] hover:bg-[#6fa86a] text-white rounded-full text-sm font-bold shadow-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download PDF
                                </a>
                            </div>

                            <div class="border border-gray-100 rounded-[30px] p-8 text-center bg-gray-50/50">
                                <svg class="w-14 h-14 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="font-bold text-gray-700 mb-1">{{ $proyek->nama_proyek }}</h3>
                                <p class="text-gray-400 text-sm">Klik tombol di atas untuk mengunduh laporan dalam format PDF.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
