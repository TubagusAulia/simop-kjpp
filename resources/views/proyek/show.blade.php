<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Back link + Project title -->
            <div class="mb-6">
                <a href="{{ route('proyek.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar Proyek
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

                                {{-- Action buttons moved to respective content panels --}}
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

                            {{-- Kontrak link moved to Detail tab --}}
                        </div>
                    </div>

                    <!-- Menu (BOTTOM) -->
                    <div class="bg-white rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] overflow-hidden">
                        <div class="p-4 border-b border-gray-100 text-center">
                            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-widest">Menu Proyek</h3>
                        </div>
                        <nav class="p-2">
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'detail']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ $activeMenu === 'detail' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">ℹ️ Detail</a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'dokumen']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ $activeMenu === 'dokumen' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">📄 Dokumen</a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'fisik']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ $activeMenu === 'fisik' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">🏠 Fisik</a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'nilai']) }}" class="block px-4 py-3 rounded-[12px] text-sm font-bold transition {{ $activeMenu === 'nilai' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">📊 Nilai</a>
                        </nav>
                    </div>
                </div>

                <!-- RIGHT CONTENT AREA -->
                <div class="flex-1">
                    <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] min-h-[500px]">

                        {{-- ==================== DETAIL TAB ==================== --}}
                        @if($activeMenu === 'detail')
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
                                                @if($u->profile_photo)<img src="{{ $u->profile_photo_url }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">@else<div class="w-[42px] h-[42px] rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 uppercase shrink-0">{{ substr($u->name, 0, 1) }}</div>@endif
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
                                                @if($u->profile_photo)<img src="{{ $u->profile_photo_url }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">@else<div class="w-[42px] h-[42px] rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 uppercase shrink-0">{{ substr($u->name, 0, 1) }}</div>@endif
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
                                                @if($u->profile_photo)<img src="{{ $u->profile_photo_url }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">@else<div class="w-[42px] h-[42px] rounded-full bg-yellow-100 flex items-center justify-center text-xs font-bold text-yellow-700 uppercase shrink-0">{{ substr($u->name, 0, 1) }}</div>@endif
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

                        {{-- ==================== DOKUMEN TAB ==================== --}}
                        @elseif($activeMenu === 'dokumen')
                            @php
                                // Compute tipe options early so they're available for the JS outside this block
                                $tipeOptionsForJs = [];
                                if ($proyek->properti) {
                                    $typeReqsForJs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
                                    $globalReqsForJs = \App\Services\DocumentRequirementService::getGlobalRequirements();
                                    $globalOptionalForJs = \App\Services\DocumentRequirementService::getGlobalOptionalRequirements();
                                    $tipeOptionsForJs = array_merge($globalReqsForJs, $typeReqsForJs['mandatory'] ?? [], $typeReqsForJs['optional'] ?? [], $globalOptionalForJs);
                                    $tipeOptionsForJs['opsional'] = 'Opsional';
                                }
                            @endphp
                            @if(!$proyek->properti)
                                <div class="bg-red-50 border border-red-100 text-red-700 p-6 rounded-[30px] text-center">
                                    <h3 class="font-bold text-lg">Properti Tidak Ditemukan</h3>
                                </div>
                            @else
                                @php
                                    $koleksiDokumen = $proyek->properti->koleksiDokumen;
                                    $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
                                    $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
                                    $verifiedTypes = $proyek->properti->dokumens->where('status', 'terverifikasi')->pluck('tipe_dokumen')->toArray();
                                    $allMandatory = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
                                @endphp

                                <h2 class="text-xl font-bold text-gray-800 mb-2">Dokumen Properti</h2>
                                <p class="text-gray-500 text-sm mb-6">Kelola dan verifikasi dokumen properti untuk proyek ini.</p>

                                {{-- Dokumen Wajib --}}
                                <div class="mb-6">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Dokumen Wajib</h3>
                                    <div class="bg-gray-50/70 rounded-[20px] p-4 space-y-1">
                                        @foreach($allMandatory as $key => $label)
                                        <label class="flex items-center gap-3 py-2 px-3 rounded-[12px] {{ in_array($key, $verifiedTypes) ? 'bg-green-50/80' : '' }}">
                                            <span class="w-5 h-5 rounded-md {{ in_array($key, $verifiedTypes) ? 'bg-[#82C17D]' : 'border-2 border-gray-300 bg-white' }} shrink-0 flex items-center justify-center">
                                                @if(in_array($key, $verifiedTypes))<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                                            </span>
                                            <span class="text-sm {{ in_array($key, $verifiedTypes) ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">{{ $label }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Dokumen --}}
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Dokumen</h3>
                                        @if(auth()->user()->isClient() || auth()->user()->isKaryawan() || auth()->user()->isAdmin())
                                        <button onclick="openDokumenCreateModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#82C17D] hover:bg-[#6fa86a] text-white rounded-full text-xs font-bold shadow-md transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Dokumen
                                        </button>
                                        @endif
                                    </div>
                                    <div class="border border-gray-100 rounded-[20px] p-4">
                                        @php
                                            $allReqs = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
                                            $typeLabels = [];
                                            foreach ($allReqs as $k => $v) { $typeLabels[$k] = $v; }
                                            // Also add optional labels
                                            if (isset($typeReqs['optional'])) {
                                                foreach ($typeReqs['optional'] as $k => $v) { $typeLabels[$k] = $v; }
                                            }
                                            // Global optional
                                            $globalOptional = \App\Services\DocumentRequirementService::getGlobalOptionalRequirements();
                                            foreach ($globalOptional as $k => $v) { $typeLabels[$k] = $v; }
                                        @endphp
                                        @forelse($proyek->properti->dokumens as $dokumen)
                                        @php
                                            $tipeOptions = array_merge($globalReqs, $typeReqs['mandatory'] ?? [], $typeReqs['optional'] ?? [], $globalOptional ?? []);
                                            $tipeOptions['opsional'] = 'Opsional';
                                            $dokData = json_encode([
                                                'id' => $dokumen->id,
                                                'nama' => $dokumen->nama_dokumen,
                                                'tipe' => $dokumen->tipe_dokumen,
                                                'tipeLabel' => $typeLabels[$dokumen->tipe_dokumen] ?? $dokumen->tipe_dokumen,
                                                'deskripsi' => $dokumen->deskripsi,
                                                'catatan' => $dokumen->catatan,
                                                'filePath' => asset('storage/' . $dokumen->file_path),
                                                'status' => $dokumen->status,
                                                'uploader' => $dokumen->uploader?->name ?? '-',
                                                'createdAt' => $dokumen->created_at->format('d M Y H:i'),
                                                'uploadedById' => $dokumen->uploaded_by,
                                                'tipeOptions' => $tipeOptions,
                                            ]);
                                        @endphp
                                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }} cursor-pointer hover:bg-gray-50 rounded-[12px] px-2 -mx-2 transition" onclick='openDokumenModal({{ $dokData }})'>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $dokumen->nama_dokumen }}</h4>
                                                <p class="text-xs text-gray-500">By: {{ $dokumen->uploader?->name }} • {{ $dokumen->created_at->diffForHumans() }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $dokumen->status === 'terverifikasi' ? 'bg-green-100 text-green-700' : ($dokumen->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $dokumen->status }}</span>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-400 italic text-center py-4">Belum ada dokumen yang diunggah.</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Action --}}
                                @if(auth()->user()->isKaryawan())
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    @if($proyek->current_phase === 'dokumen')
                                    <form action="{{ route('proyek.selesai-verifikasi-dokumen', $proyek->id) }}" method="POST" onsubmit="return confirm('Yakin verifikasi dokumen sudah selesai? Fase akan berpindah ke Verifikasi Fisik.')">@csrf
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-5 py-3 rounded-full text-sm font-bold shadow-md transition"
                                            {{ ($koleksiDokumen && $koleksiDokumen->isAllVerified()) ? '' : 'disabled' }}>
                                            Selesai Verifikasi Dokumen, Lanjut ke fase berikutnya
                                        </button>
                                    </form>
                                    @if(!($koleksiDokumen && $koleksiDokumen->isAllVerified()))
                                    <p class="text-xs text-gray-400 mt-2 text-center">Semua dokumen wajib harus diverifikasi terlebih dahulu.</p>
                                    @endif
                                    @else
                                    <button type="button" disabled class="w-full bg-gray-300 text-white px-5 py-3 rounded-full text-sm font-bold shadow-md cursor-not-allowed">Selesai Verifikasi Dokumen, Lanjut ke fase berikutnya</button>
                                    @endif
                                </div>
                                @endif
                            @endif

                        {{-- ==================== FISIK TAB ==================== --}}
                        @elseif($activeMenu === 'fisik')
                            @if(!$proyek->properti)
                                <div class="bg-red-50 border border-red-100 text-red-700 p-6 rounded-[30px] text-center">
                                    <h3 class="font-bold text-lg">Properti Tidak Ditemukan</h3>
                                </div>
                            @else
                                @php
                                    $koleksiFisik = $proyek->properti->koleksiFisik;
                                    $checklistWajib = $proyek->properti->checklistFisiks ?? collect();
                                    $allAspekFisik = $proyek->properti->aspekFisiks ?? collect();
                                @endphp

                                <h2 class="text-xl font-bold text-gray-800 mb-2">Fisik Properti</h2>
                                <p class="text-gray-500 text-sm mb-6">Kelola dan verifikasi aspek fisik properti untuk proyek ini.</p>

                                {{-- Aspek Fisik Wajib --}}
                                <div class="mb-6">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Aspek Fisik Wajib</h3>
                                    <div class="bg-gray-50/70 rounded-[20px] p-4 space-y-1">
                                        @forelse($checklistWajib as $item)
                                        @php $vStatus = $item->verificationStatus(); @endphp
                                        <label class="flex items-center gap-3 py-2 px-3 rounded-[12px] {{ $vStatus === 'terverifikasi' ? 'bg-green-50/80' : '' }}">
                                            <span class="w-5 h-5 rounded-md {{ $vStatus === 'terverifikasi' ? 'bg-[#82C17D]' : ($vStatus === 'ditolak' ? 'border-2 border-red-400 bg-red-50' : 'border-2 border-gray-300 bg-white') }} shrink-0 flex items-center justify-center">
                                                @if($vStatus === 'terverifikasi')<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                                            </span>
                                            <span class="text-sm {{ $vStatus === 'terverifikasi' ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">{{ $item->nama_item }}</span>
                                        </label>
                                        @empty
                                        <p class="text-sm text-gray-400 italic text-center py-4">Belum ada checklist.</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Aspek Fisik --}}
                                <div class="mb-6">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Aspek Fisik</h3>
                                    <div class="border border-gray-100 rounded-[20px] p-4">
                                        @forelse($allAspekFisik->sortByDesc('created_at') as $aspek)
                                        @php
                                            $fotoList = is_array($aspek->foto_paths) ? $aspek->foto_paths : json_decode($aspek->foto_paths, true) ?? [];
                                            $aspekData = json_encode([
                                                'id' => $aspek->id,
                                                'nama' => $aspek->nama_aspek,
                                                'tipe' => $aspek->tipe,
                                                'deskripsi' => $aspek->deskripsi,
                                                'lat' => $aspek->latitude,
                                                'lng' => $aspek->longitude,
                                                'fotos' => $fotoList,
                                                'status' => $aspek->status,
                                                'catatan' => $aspek->catatan,
                                                'creator' => $aspek->creator?->name ?? '-',
                                                'createdAt' => $aspek->created_at->format('d M Y H:i'),
                                                'createdById' => $aspek->created_by,
                                            ]);
                                        @endphp
                                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }} cursor-pointer hover:bg-gray-50 rounded-[12px] px-2 -mx-2 transition" onclick='openFisikModal({{ $aspekData }})'>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $aspek->nama_aspek }}</h4>
                                                <p class="text-xs text-gray-500">Oleh: {{ $aspek->creator?->name ?? '-' }} • {{ $aspek->created_at->diffForHumans() }}</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $aspek->status === 'terverifikasi' ? 'bg-green-100 text-green-700' : ($aspek->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $aspek->status }}</span>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-400 italic text-center py-4">Belum ada aspek fisik yang ditambahkan.</p>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Action --}}
                                @if(auth()->user()->isKaryawan())
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    @if($proyek->current_phase === 'fisik')
                                    <form action="{{ route('proyek.selesai-verifikasi-fisik', $proyek->id) }}" method="POST" onsubmit="return confirm('Yakin verifikasi fisik sudah selesai? Fase akan berpindah ke Penilaian Properti.')">@csrf
                                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-5 py-3 rounded-full text-sm font-bold shadow-md transition"
                                            {{ ($koleksiFisik && $koleksiFisik->isAllVerified()) ? '' : 'disabled' }}>
                                            Selesai Verifikasi Fisik, Lanjut ke fase berikutnya
                                        </button>
                                    </form>
                                    @if(!($koleksiFisik && $koleksiFisik->isAllVerified()))
                                    <p class="text-xs text-gray-400 mt-2 text-center">Semua aspek fisik wajib harus diverifikasi terlebih dahulu.</p>
                                    @endif
                                    @else
                                    <button type="button" disabled class="w-full bg-gray-300 text-white px-5 py-3 rounded-full text-sm font-bold shadow-md cursor-not-allowed">Selesai Verifikasi Fisik, Lanjut ke fase berikutnya</button>
                                    @endif
                                </div>
                                @endif
                            @endif

                        {{-- ==================== NILAI TAB ==================== --}}
                        @elseif($activeMenu === 'nilai')
                            @if(!$proyek->properti)
                                <div class="bg-red-50 border border-red-100 text-red-700 p-6 rounded-[30px] text-center">
                                    <h3 class="font-bold text-lg">Properti Tidak Ditemukan</h3>
                                </div>
                            @else
                                @php
                                    $koleksiNilai = $proyek->properti->koleksiNilai;
                                    $nilaiData = $proyek->properti?->nilai;
                                    $isLocked = $proyek->current_phase !== 'dinilai';
                                @endphp

                                <h2 class="text-xl font-bold text-gray-800 mb-2">Penilaian Properti</h2>
                                <p class="text-gray-500 text-sm mb-6">Nilai Properti untuk Proyek ini.</p>

                                {{-- Nilai Properti --}}
                                <div class="mb-6">
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Nilai Properti</h3>
                                    <div class="border border-gray-100 rounded-[20px] p-4">
                                        @if($isLocked || !auth()->user()->isKaryawan())
                                            {{-- Locked or non-karyawan: show read-only nilai --}}
                                            @if($nilaiData)
                                            <p class="text-2xl font-bold text-[#82C17D]">Rp {{ number_format($nilaiData->nilai, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-400 mt-1">Dinilai oleh: {{ $nilaiData->creator?->name ?? '-' }}</p>
                                            @if($nilaiData->catatan)
                                            <p class="text-sm text-gray-600 mt-2">{{ $nilaiData->catatan }}</p>
                                            @endif
                                            @else
                                            <p class="text-sm text-gray-400 italic">Belum ada penilaian.</p>
                                            @endif
                                        @else
                                        {{-- Editable form for karyawan/admin in dinilai phase --}}
                                        <form action="{{ route('properti.nilai.save', $proyek->properti->id) }}" method="POST" class="space-y-4" id="nilaiForm">
                                            @csrf
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Properti</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                                    <input type="text" id="nilaiInput" required
                                                        value="{{ old('nilai', $nilaiData->nilai ? number_format($nilaiData->nilai, 0, ',', '.') : '') }}"
                                                        placeholder="0"
                                                        class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm font-semibold">
                                                </div>
                                                <input type="hidden" name="nilai" id="nilaiRaw" value="{{ old('nilai', $nilaiData->nilai ?? '') }}">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1">Catatan Penilaian</label>
                                                <textarea name="catatan" rows="4" placeholder="Berikan catatan/alasan penilaian..."
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 px-4 text-sm">{{ old('catatan', $nilaiData->catatan ?? '') }}</textarea>
                                            </div>
                                            <button type="submit" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-3 rounded-full text-sm font-bold shadow-md transition">{{ $nilaiData ? 'Update Penilaian' : 'Simpan Penilaian' }}</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action: Selesai Penilaian (only karyawan) --}}
                                @if(auth()->user()->isKaryawan() && $proyek->current_phase === 'dinilai')
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    @if($nilaiData)
                                    <form action="{{ route('proyek.selesai-penilaian', $proyek->id) }}" method="POST" onsubmit="return confirm('Yakin penilaian sudah selesai?')">@csrf
                                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-full text-sm font-bold shadow-md transition">Selesai Menilai Proyek, Selesaikan Proyek</button>
                                    </form>
                                    @else
                                    <button type="button" disabled class="w-full bg-gray-300 text-white px-5 py-3 rounded-full text-sm font-bold shadow-md cursor-not-allowed">Selesai Menilai Proyek, Selesaikan Proyek</button>
                                    <p class="text-xs text-gray-400 mt-2 text-center">Penilaian harus diisi terlebih dahulu.</p>
                                    @endif
                                </div>
                                @endif

                                @if(auth()->user()->isAdmin() && $proyek->finish_requested && $proyek->current_phase === 'dinilai')
                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-100 rounded-[20px]">
                                    <p class="text-sm font-bold text-yellow-800 mb-2">Permintaan penyelesaian proyek dari {{ $proyek->finishRequester?->name ?? 'Karyawan' }}</p>
                                    <form action="{{ route('proyek.accept-finish', $proyek->id) }}" method="POST">@csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-full text-sm font-bold shadow-md transition">Setujui Penyelesaian Proyek</button>
                                    </form>
                                </div>
                                @endif
                            @endif

                        @endif
                        {{-- End of activeMenu checks --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== DOKUMEN DETAIL MODAL ==================== --}}
    <div id="dokumenModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeDokumenModal()">
        <div class="bg-white rounded-[30px] w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 class="font-bold text-lg" id="dokumenModalTitle">Detail Dokumen</h3>
                <button onclick="closeDokumenModal()" class="hover:bg-white/20 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto space-y-4">
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Nama</label>
                    <p class="text-sm font-semibold text-gray-800 dokumen-field-readonly" id="dokumenNama">-</p>
                    <input type="text" id="dokumenNamaInput" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]" />
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Tipe</label>
                    <p class="text-sm font-semibold text-gray-800 dokumen-field-readonly" id="dokumenTipe">-</p>
                    <select id="dokumenTipeSelect" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]"></select>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Deskripsi</label>
                    <p class="text-sm text-gray-600 dokumen-field-readonly" id="dokumenDeskripsi">-</p>
                    <textarea id="dokumenDeskripsiInput" rows="2" placeholder="Deskripsi..." class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Catatan</label>
                    <p class="text-sm text-gray-600 dokumen-field-readonly" id="dokumenCatatan">-</p>
                    <textarea id="dokumenCatatanInput" rows="2" placeholder="Catatan..." class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-2">Dokumen</label>
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <iframe id="dokumenPreview" src="" class="w-full h-[300px] border-0"></iframe>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button id="dokumenViewBtn" class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-full text-xs font-bold transition flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View
                        </button>
                        <a id="dokumenDownloadBtn" href="#" download class="flex-1 bg-[#82C17D]/10 text-[#82C17D] hover:bg-[#82C17D]/20 px-4 py-2 rounded-full text-xs font-bold transition flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                    </div>
                    <div id="dokumenFileUpload" class="hidden mt-2">
                        <label class="block text-[10px] text-gray-400 font-bold mb-1">Ganti File (opsional)</label>
                        <input type="file" id="dokumenFileInput" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300" />
                    </div>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Status</label>
                    <div id="dokumenStatus" class="mt-1 dokumen-field-readonly"></div>
                    <select id="dokumenStatusSelect" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]">
                        <option value="menunggu">Menunggu</option>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <p class="text-xs text-gray-400 italic" id="dokumenMeta">-</p>
            </div>
            <div class="p-6 pt-0 border-t border-gray-50 mt-auto shrink-0 flex justify-end gap-3">
                <button onclick="closeDokumenModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-full text-sm font-bold transition">Kembali</button>
                <button id="dokumenActionBtn" class="hidden bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-2.5 rounded-full text-sm font-bold transition">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ==================== FISIK DETAIL MODAL ==================== --}}
    <div id="fisikModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeFisikModal()">
        <div class="bg-white rounded-[30px] w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 class="font-bold text-lg" id="fisikModalTitle">Detail Aspek Fisik</h3>
                <button onclick="closeFisikModal()" class="hover:bg-white/20 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto space-y-4">
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Nama</label>
                    <p class="text-sm font-semibold text-gray-800 fisik-field-readonly" id="fisikNama">-</p>
                    <input type="text" id="fisikNamaInput" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]" />
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Tipe</label>
                    <p class="text-sm font-semibold text-gray-800 fisik-field-readonly" id="fisikTipe">-</p>
                    <select id="fisikTipeSelect" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]">
                        <option value="wajib">Wajib</option>
                        <option value="opsional">Opsional</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Deskripsi</label>
                    <p class="text-sm text-gray-600 fisik-field-readonly" id="fisikDeskripsi">-</p>
                    <textarea id="fisikDeskripsiInput" rows="2" placeholder="Deskripsi..." class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-2">Peta</label>
                    <div class="grid grid-cols-2 gap-3 mb-2">
                        <div class="bg-gray-50 rounded-xl px-4 py-2">
                            <span class="text-[10px] text-gray-400 font-bold">Latitude</span>
                            <p class="text-sm font-semibold text-gray-700 fisik-field-readonly" id="fisikLat">-</p>
                            <input type="text" id="fisikLatInput" class="hidden w-full rounded-lg border-gray-200 bg-white py-1 px-2 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]" />
                        </div>
                        <div class="bg-gray-50 rounded-xl px-4 py-2">
                            <span class="text-[10px] text-gray-400 font-bold">Longitude</span>
                            <p class="text-sm font-semibold text-gray-700 fisik-field-readonly" id="fisikLng">-</p>
                            <input type="text" id="fisikLngInput" class="hidden w-full rounded-lg border-gray-200 bg-white py-1 px-2 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]" />
                        </div>
                    </div>
                    <div id="fisikMap" class="w-full h-[200px] rounded-xl z-0 border border-gray-200 hidden"></div>
                    <p id="fisikNoMap" class="text-xs text-gray-400 italic hidden">Tidak ada koordinat.</p>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-2">Foto</label>
                    <div id="fisikFotos" class="grid grid-cols-3 gap-2"></div>
                    <p id="fisikNoFoto" class="text-xs text-gray-400 italic hidden">Tidak ada foto.</p>
                    <div id="fisikFotoUpload" class="hidden mt-2">
                        <label class="block text-[10px] text-gray-400 font-bold mb-1">Tambah Foto (opsional)</label>
                        <input type="file" id="fisikFotoInput" multiple accept="image/jpeg,image/png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300" />
                    </div>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Catatan</label>
                    <p class="text-sm text-gray-600 fisik-field-readonly" id="fisikCatatanText">-</p>
                    <textarea id="fisikCatatanInput" rows="2" placeholder="Catatan verifikasi..." class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Status</label>
                    <div id="fisikStatus" class="mt-1 fisik-field-readonly"></div>
                    <select id="fisikStatusSelect" class="hidden w-full rounded-xl border-gray-200 bg-gray-50 py-2 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]">
                        <option value="menunggu">Menunggu</option>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <p class="text-xs text-gray-400 italic" id="fisikMeta">-</p>
            </div>
            <div class="p-6 pt-0 border-t border-gray-50 mt-auto shrink-0 flex justify-end gap-3">
                <button onclick="closeFisikModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-full text-sm font-bold transition">Kembali</button>
                <button id="fisikActionBtn" class="hidden bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-2.5 rounded-full text-sm font-bold transition">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ==================== TAMBAH DOKUMEN MODAL ==================== --}}
    <div id="dokumenCreateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="if(event.target===this) closeDokumenCreateModal()">
        <div class="bg-white rounded-[30px] w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 class="font-bold text-lg">Tambah Dokumen</h3>
                <button onclick="closeDokumenCreateModal()" class="hover:bg-white/20 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto space-y-4">
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Nama Dokumen</label>
                    <input type="text" id="dokumenCreateNama" placeholder="Nama dokumen..." class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]" />
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Tipe Dokumen</label>
                    <select id="dokumenCreateTipe" class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 px-4 text-sm font-semibold focus:border-[#82C17D] focus:ring-[#82C17D]">
                        <option value="" disabled selected>Pilih tipe...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Deskripsi</label>
                    <textarea id="dokumenCreateDeskripsi" rows="2" placeholder="Deskripsi..." class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Catatan</label>
                    <textarea id="dokumenCreateCatatan" rows="2" placeholder="Catatan..." class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 px-4 text-sm focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Unggah File</label>
                    <input type="file" id="dokumenCreateFile" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300" />
                    <p class="text-[10px] text-gray-400 mt-1">Format: PDF, JPG, JPEG, PNG — Maks 10MB</p>
                </div>
            </div>
            <div class="p-6 pt-0 border-t border-gray-50 mt-auto shrink-0 flex justify-end gap-3">
                <button onclick="closeDokumenCreateModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-full text-sm font-bold transition">Batal</button>
                <button id="dokumenCreateBtn" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-2.5 rounded-full text-sm font-bold transition">Unggah</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // ── Dokumen type options (for create modal) ──
        var dokumenTipeOptions = @json($tipeOptionsForJs ?? []);
        var dokumenPropertiId = {{ $proyek->properti->id ?? 'null' }};

        // ── Status badge helper ──
        function statusBadge(status) {
            var s = status.toLowerCase();
            var cls = s === 'terverifikasi' ? 'bg-green-100 text-green-700'
                    : s === 'menunggu' ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-red-100 text-red-700';
            return '<span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ' + cls + '">' + status + '</span>';
        }

        // ── CSRF token ──
        var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        // ── Current user role (set by Blade) ──
        var currentUserRole = '{{ auth()->user()->role }}';
        var currentUserId = {{ auth()->id() }};

        // ══════════════════════════════════════════════════════════
        // DOKUMEN MODAL
        // ══════════════════════════════════════════════════════════

        function dokumenResetUI() {
            // Hide all inputs, show all read-only fields
            document.querySelectorAll('.dokumen-field-readonly').forEach(function(el) { el.classList.remove('hidden'); });
            document.getElementById('dokumenNamaInput').classList.add('hidden');
            document.getElementById('dokumenTipeSelect').classList.add('hidden');
            document.getElementById('dokumenDeskripsiInput').classList.add('hidden');
            document.getElementById('dokumenCatatanInput').classList.add('hidden');
            document.getElementById('dokumenFileUpload').classList.add('hidden');
            document.getElementById('dokumenStatusSelect').classList.add('hidden');
            document.getElementById('dokumenActionBtn').classList.add('hidden');
        }

        function openDokumenModal(data) {
            dokumenResetUI();

            document.getElementById('dokumenModalTitle').textContent = data.nama;
            document.getElementById('dokumenNama').textContent = data.nama;
            document.getElementById('dokumenTipe').textContent = data.tipeLabel;
            document.getElementById('dokumenDeskripsi').textContent = data.deskripsi || '-';
            document.getElementById('dokumenCatatan').textContent = data.catatan || '-';
            document.getElementById('dokumenPreview').src = data.filePath;
            document.getElementById('dokumenViewBtn').onclick = function() { window.open(data.filePath, '_blank'); };
            document.getElementById('dokumenDownloadBtn').href = data.filePath;
            document.getElementById('dokumenStatus').innerHTML = statusBadge(data.status);
            document.getElementById('dokumenMeta').textContent = 'diupload oleh ' + data.uploader + ' pada ' + data.createdAt;

            var actionBtn = document.getElementById('dokumenActionBtn');

            if (currentUserRole === 'karyawan' || currentUserRole === 'admin') {
                // Karyawan/Admin: can only change status + catatan
                document.getElementById('dokumenCatatan').classList.add('hidden');
                document.getElementById('dokumenCatatanInput').classList.remove('hidden');
                document.getElementById('dokumenCatatanInput').value = data.catatan || '';

                document.getElementById('dokumenStatus').classList.add('hidden');
                document.getElementById('dokumenStatusSelect').classList.remove('hidden');
                document.getElementById('dokumenStatusSelect').value = data.status;

                actionBtn.textContent = 'Simpan Verifikasi';
                actionBtn.classList.remove('hidden');
                actionBtn.onclick = function() {
                    var formData = new FormData();
                    formData.append('status', document.getElementById('dokumenStatusSelect').value);
                    formData.append('catatan', document.getElementById('dokumenCatatanInput').value);
                    formData.append('_token', csrfToken);

                    fetch('/dokumen/' + data.id + '/verifikasi', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        body: formData
                    })
                    .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
                    .then(function(r) {
                        if (r.ok) { closeDokumenModal(); location.reload(); }
                        else { alert('Gagal: ' + (r.data && r.data.message || 'Terjadi kesalahan.')); }
                    })
                    .catch(function(e) { alert('Error: ' + e.message); });
                };
            } else if (currentUserRole === 'client' && data.uploadedById == currentUserId) {
                if (data.status === 'terverifikasi') {
                    // Already verified — read-only, show a note
                    document.getElementById('dokumenMeta').textContent = 'Dokumen ini sudah terverifikasi dan tidak dapat diubah.';
                } else {
                    // Client: can edit everything except status when not yet verified
                    document.getElementById('dokumenNama').classList.add('hidden');
                    document.getElementById('dokumenNamaInput').classList.remove('hidden');
                    document.getElementById('dokumenNamaInput').value = data.nama;

                    document.getElementById('dokumenTipe').classList.add('hidden');
                    document.getElementById('dokumenTipeSelect').classList.remove('hidden');
                    // Populate tipe options
                    var tipeSelect = document.getElementById('dokumenTipeSelect');
                    tipeSelect.innerHTML = '';
                    if (data.tipeOptions) {
                        Object.keys(data.tipeOptions).forEach(function(key) {
                            var opt = document.createElement('option');
                            opt.value = key;
                            opt.textContent = data.tipeOptions[key];
                            if (key === data.tipe) opt.selected = true;
                            tipeSelect.appendChild(opt);
                        });
                    }

                    document.getElementById('dokumenDeskripsi').classList.add('hidden');
                    document.getElementById('dokumenDeskripsiInput').classList.remove('hidden');
                    document.getElementById('dokumenDeskripsiInput').value = data.deskripsi || '';

                    document.getElementById('dokumenCatatan').classList.add('hidden');
                    document.getElementById('dokumenCatatanInput').classList.remove('hidden');
                    document.getElementById('dokumenCatatanInput').value = data.catatan || '';

                    document.getElementById('dokumenFileUpload').classList.remove('hidden');

                    actionBtn.textContent = 'Simpan Perubahan';
                    actionBtn.classList.remove('hidden');
                    actionBtn.onclick = function() {
                        var formData = new FormData();
                        formData.append('nama_dokumen', document.getElementById('dokumenNamaInput').value);
                        formData.append('tipe_dokumen', document.getElementById('dokumenTipeSelect').value);
                        formData.append('deskripsi', document.getElementById('dokumenDeskripsiInput').value);
                        formData.append('catatan', document.getElementById('dokumenCatatanInput').value);
                        var fileInput = document.getElementById('dokumenFileInput');
                        if (fileInput && fileInput.files.length > 0) {
                            formData.append('file', fileInput.files[0]);
                        }

                        formData.append('_method', 'PUT');
                        fetch('/dokumen/' + data.id, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            body: formData
                        })
                        .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
                        .then(function(r) {
                            if (r.ok) { closeDokumenModal(); location.reload(); }
                            else { alert('Gagal: ' + (r.data && r.data.message || 'Terjadi kesalahan.')); }
                        })
                        .catch(function(e) { alert('Error: ' + e.message); });
                    };
                }
            }
            // else: read-only, no action button (already hidden by reset)

            document.getElementById('dokumenModal').classList.remove('hidden');
        }

        function closeDokumenModal() {
            document.getElementById('dokumenModal').classList.add('hidden');
            document.getElementById('dokumenPreview').src = '';
            dokumenResetUI();
        }

        // ══════════════════════════════════════════════════════════
        // DOKUMEN CREATE MODAL
        // ══════════════════════════════════════════════════════════

        function openDokumenCreateModal() {
            // Reset form
            document.getElementById('dokumenCreateNama').value = '';
            document.getElementById('dokumenCreateDeskripsi').value = '';
            document.getElementById('dokumenCreateCatatan').value = '';
            document.getElementById('dokumenCreateFile').value = '';

            // Populate tipe options
            var select = document.getElementById('dokumenCreateTipe');
            select.innerHTML = '<option value="" disabled selected>Pilih tipe...</option>';
            if (dokumenTipeOptions) {
                Object.keys(dokumenTipeOptions).forEach(function(key) {
                    var opt = document.createElement('option');
                    opt.value = key;
                    opt.textContent = dokumenTipeOptions[key];
                    select.appendChild(opt);
                });
            }

            document.getElementById('dokumenCreateModal').classList.remove('hidden');
        }

        function closeDokumenCreateModal() {
            document.getElementById('dokumenCreateModal').classList.add('hidden');
        }

        // Wire create button
        document.getElementById('dokumenCreateBtn').addEventListener('click', function() {
            var nama = document.getElementById('dokumenCreateNama').value.trim();
            var tipe = document.getElementById('dokumenCreateTipe').value;
            var fileInput = document.getElementById('dokumenCreateFile');

            if (!nama) { alert('Nama dokumen wajib diisi.'); return; }
            if (!tipe) { alert('Tipe dokumen wajib dipilih.'); return; }
            if (!fileInput.files.length) { alert('File dokumen wajib diunggah.'); return; }

            var formData = new FormData();
            formData.append('nama_dokumen', nama);
            formData.append('tipe_dokumen', tipe);
            formData.append('deskripsi', document.getElementById('dokumenCreateDeskripsi').value);
            formData.append('catatan', document.getElementById('dokumenCreateCatatan').value);
            formData.append('file', fileInput.files[0]);

            this.disabled = true;
            this.textContent = 'Mengunggah...';

            fetch('/properti/' + dokumenPropertiId + '/dokumen', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
            .then(function(r) {
                if (r.ok) { closeDokumenCreateModal(); location.reload(); }
                else { alert('Gagal: ' + (r.data && r.data.message || 'Terjadi kesalahan.')); }
            })
            .catch(function(e) { alert('Error: ' + e.message); })
            .finally(function() {
                document.getElementById('dokumenCreateBtn').disabled = false;
                document.getElementById('dokumenCreateBtn').textContent = 'Unggah';
            });
        });

        // ══════════════════════════════════════════════════════════
        // FISIK MODAL
        // ══════════════════════════════════════════════════════════
        var fisikMap = null;
        var fisikMarker = null;

        function fisikResetUI() {
            document.querySelectorAll('.fisik-field-readonly').forEach(function(el) { el.classList.remove('hidden'); });
            document.getElementById('fisikNamaInput').classList.add('hidden');
            document.getElementById('fisikTipeSelect').classList.add('hidden');
            document.getElementById('fisikDeskripsiInput').classList.add('hidden');
            document.getElementById('fisikLatInput').classList.add('hidden');
            document.getElementById('fisikLngInput').classList.add('hidden');
            document.getElementById('fisikFotoUpload').classList.add('hidden');
            document.getElementById('fisikCatatanText').classList.remove('hidden');
            document.getElementById('fisikCatatanInput').classList.add('hidden');
            document.getElementById('fisikStatusSelect').classList.add('hidden');
            document.getElementById('fisikActionBtn').classList.add('hidden');
        }

        function openFisikModal(data) {
            fisikResetUI();

            document.getElementById('fisikModalTitle').textContent = data.nama;
            document.getElementById('fisikNama').textContent = data.nama;
            document.getElementById('fisikTipe').textContent = data.tipe;
            document.getElementById('fisikDeskripsi').textContent = data.deskripsi || '-';
            document.getElementById('fisikLat').textContent = data.lat || '-';
            document.getElementById('fisikLng').textContent = data.lng || '-';
            document.getElementById('fisikStatus').innerHTML = statusBadge(data.status);
            document.getElementById('fisikCatatanText').textContent = data.catatan || '-';
            document.getElementById('fisikMeta').textContent = 'dibuat oleh ' + data.creator + ' pada ' + data.createdAt;

            // Fotos
            var fotoContainer = document.getElementById('fisikFotos');
            var noFoto = document.getElementById('fisikNoFoto');
            fotoContainer.innerHTML = '';
            if (data.fotos && data.fotos.length > 0) {
                noFoto.classList.add('hidden');
                data.fotos.forEach(function(foto) {
                    var url = '{{ asset('storage/') }}/' + foto;
                    var img = document.createElement('img');
                    img.src = url;
                    img.className = 'w-full h-24 object-cover rounded-xl cursor-pointer hover:opacity-80 transition';
                    img.onclick = function() { window.open(url, '_blank'); };
                    fotoContainer.appendChild(img);
                });
            } else {
                noFoto.classList.remove('hidden');
            }

            // Map
            var mapDiv = document.getElementById('fisikMap');
            var noMap = document.getElementById('fisikNoMap');
            if (data.lat && data.lng) {
                noMap.classList.add('hidden');
                mapDiv.classList.remove('hidden');
                setTimeout(function() {
                    if (fisikMap) { fisikMap.remove(); fisikMap = null; }
                    fisikMap = L.map('fisikMap').setView([data.lat, data.lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(fisikMap);
                    fisikMarker = L.marker([data.lat, data.lng]).addTo(fisikMap);
                    fisikMap.invalidateSize();
                }, 100);
            } else {
                mapDiv.classList.add('hidden');
                noMap.classList.remove('hidden');
            }

            var actionBtn = document.getElementById('fisikActionBtn');

            if (currentUserRole === 'karyawan' || currentUserRole === 'mitra' || currentUserRole === 'admin') {
                if (data.status === 'terverifikasi') {
                    // Already verified — read-only, show a note
                    document.getElementById('fisikMeta').textContent = 'Aspek fisik ini sudah terverifikasi dan tidak dapat diubah.';
                } else {
                    // Karyawan/Mitra/Admin: can change status + catatan
                    document.getElementById('fisikCatatanText').classList.add('hidden');
                    document.getElementById('fisikCatatanInput').classList.remove('hidden');
                    document.getElementById('fisikCatatanInput').value = data.catatan || '';

                    document.getElementById('fisikStatus').classList.add('hidden');
                    document.getElementById('fisikStatusSelect').classList.remove('hidden');
                    document.getElementById('fisikStatusSelect').value = data.status;

                    actionBtn.textContent = 'Simpan Verifikasi';
                    actionBtn.classList.remove('hidden');
                    actionBtn.onclick = function() {
                        var formData = new FormData();
                        formData.append('status', document.getElementById('fisikStatusSelect').value);
                        formData.append('catatan', document.getElementById('fisikCatatanInput').value);

                        fetch('/aspek-fisik/' + data.id + '/verifikasi', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken },
                            body: formData
                        })
                        .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
                        .then(function(r) {
                            if (r.ok) { closeFisikModal(); location.reload(); }
                            else { alert('Gagal: ' + (r.data && r.data.message || 'Terjadi kesalahan.')); }
                        })
                        .catch(function(e) { alert('Error: ' + e.message); });
                    };
                }
            }
            // else: read-only, no action button (already hidden by reset)

            document.getElementById('fisikModal').classList.remove('hidden');
        }

        function closeFisikModal() {
            document.getElementById('fisikModal').classList.add('hidden');
            if (fisikMap) { fisikMap.remove(); fisikMap = null; fisikMarker = null; }
            fisikResetUI();
        }

        // ── Global close: Escape key + click outside ──
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDokumenModal();
                closeDokumenCreateModal();
                closeFisikModal();
            }
        });

        // ── Rp input formatting (existing) ──
        (function() {
            var display = document.getElementById('nilaiInput');
            var raw = document.getElementById('nilaiRaw');
            if (!display || !raw) return;

            function formatRp(val) {
                var num = val.replace(/\D/g, '');
                if (!num) return '0';
                return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            display.addEventListener('input', function() {
                var num = this.value.replace(/\D/g, '');
                raw.value = num;
                var cursorPos = this.selectionStart;
                var oldLen = this.value.length;
                this.value = formatRp(this.value);
                var newLen = this.value.length;
                this.setSelectionRange(cursorPos + (newLen - oldLen), cursorPos + (newLen - oldLen));
            });
        })();
    </script>
    @endpush
</x-app-layout>
