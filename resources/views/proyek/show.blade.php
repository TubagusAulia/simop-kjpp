<x-app-layout>
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
                        <p class="text-sm text-gray-500 mt-1">Dibuat oleh: {{ $proyek->creator?->name ?? '-' }}</p>
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
                            @php
                                $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
                            @endphp
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
                                $phaseLabels = [
                                    'dimulai' => 'Proyek Dimulai',
                                    'dokumen' => 'Dokumen Diverifikasi',
                                    'fisik' => 'Fisik Diverifikasi',
                                    'dinilai' => 'Properti Dinilai',
                                    'selesai' => 'Proyek Selesai',
                                ];
                                $currentPhaseLabel = $phaseLabels[$proyek->current_phase ?? 'dimulai'] ?? 'Proyek Dimulai';
                            @endphp
                            <div>
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest block mb-1">Status</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if(($proyek->current_phase ?? 'dimulai') === 'selesai') bg-green-100 text-green-700
                                    @elseif(($proyek->current_phase ?? 'dimulai') === 'dinilai') bg-purple-100 text-purple-700
                                    @elseif(($proyek->current_phase ?? 'dimulai') === 'fisik') bg-yellow-100 text-yellow-700
                                    @elseif(($proyek->current_phase ?? 'dimulai') === 'dokumen') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ $currentPhaseLabel }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest block mb-1">Durasi</span>
                                <span class="text-gray-800 font-bold">{{ $proyek->start_date->format('d M Y') }} - {{ $proyek->due_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest block mb-1">Peserta</span>
                                <span class="text-gray-800 font-bold">{{ $proyek->users->count() }} orang terlibat</span>
                            </div>
                            @if($proyek->kontrak_file)
                            <div class="pt-2">
                                <a href="{{ asset('storage/' . $proyek->kontrak_file) }}" target="_blank"
                                    class="flex items-center gap-2 p-2 bg-gray-50 hover:bg-gray-100 rounded-xl text-[#82C17D] font-bold text-xs transition border border-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Lihat Berkas Kontrak
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Menu (BOTTOM) -->
                    <div class="bg-white rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)] overflow-hidden">
                        <div class="p-4 border-b border-gray-100 text-center">
                            <h3 class="font-bold text-gray-800 text-[11px] uppercase tracking-widest">Menu Proyek</h3>
                        </div>
                        <nav class="p-2">
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'detail']) }}"
                                class="block px-4 py-3 rounded-[12px] text-sm font-bold transition
                                {{ $activeMenu === 'detail' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                                ℹ️ Detail
                            </a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'dokumen']) }}"
                                class="block px-4 py-3 rounded-[12px] text-sm font-bold transition
                                {{ $activeMenu === 'dokumen' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                                📄 Dokumen
                            </a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'fisik']) }}"
                                class="block px-4 py-3 rounded-[12px] text-sm font-bold transition
                                {{ $activeMenu === 'fisik' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                                🏠 Fisik
                            </a>
                            <a href="{{ route('proyek.show', ['proyek' => $proyek->id, 'menu' => 'nilai']) }}"
                                class="block px-4 py-3 rounded-[12px] text-sm font-bold transition
                                {{ $activeMenu === 'nilai' ? 'bg-[#82C17D] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50' }}">
                                📊 Nilai
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- RIGHT CONTENT AREA -->
                <div class="flex-1">
                    <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] min-h-[500px]">

                        @if($activeMenu === 'detail')
                            <!-- DETAIL CONTENT -->
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Penilaian</h2>
                            <p class="text-gray-500 mb-6">Informasi identitas dan kontrak proyek.</p>

                            <!-- Project Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Informasi Umum (includes deskripsi) -->
                                <div class="border border-gray-100 rounded-[30px] p-6">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Informasi Umum
                                    </h3>
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

                                <!-- Participants -->
                                <div class="border border-gray-100 rounded-[30px] p-6">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-4m1-4a4 4 0 11-8 0 4 4 0 018 0zm12 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        Peserta Terlibat
                                    </h3>
                                    <div class="space-y-4">
                                        @php
                                            $karyawans = $proyek->users->where('role', 'karyawan');
                                            $clients = $proyek->users->where('role', 'client');
                                            $mitras = $proyek->users->where('role', 'mitra');
                                        @endphp

                                        @if($karyawans->isNotEmpty())
                                            <div>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Internal (Karyawan)</p>
                                                <div class="space-y-4">
                                                    @foreach($karyawans as $userList)
                                                        <div class="flex items-center gap-3">
                                                            @if($userList->profile_photo)
                                                                <img src="{{ $userList->profile_photo_url }}" alt="{{ $userList->name }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">
                                                            @else
                                                                <div class="w-[42px] h-[42px] rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 uppercase shrink-0">
                                                                    {{ substr($userList->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div class="min-w-0">
                                                                <p class="text-gray-800 font-semibold text-sm truncate">{{ $userList->name }}</p>
                                                                <p class="text-[10px] text-gray-400 font-bold tracking-widest truncate">{{ $userList->username }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($clients->isNotEmpty())
                                            <div>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Eksternal (Klien)</p>
                                                <div class="space-y-4">
                                                    @foreach($clients as $userList)
                                                        <div class="flex items-center gap-3">
                                                            @if($userList->profile_photo)
                                                                <img src="{{ $userList->profile_photo_url }}" alt="{{ $userList->name }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">
                                                            @else
                                                                <div class="w-[42px] h-[42px] rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-green-700 uppercase shrink-0">
                                                                    {{ substr($userList->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div class="min-w-0">
                                                                <p class="text-gray-800 font-semibold text-sm truncate">{{ $userList->name }}</p>
                                                                <p class="text-[10px] text-gray-400 font-bold tracking-widest truncate">{{ $userList->username }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($mitras->isNotEmpty())
                                            <div>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Partner (Mitra)</p>
                                                <div class="space-y-4">
                                                    @foreach($mitras as $userList)
                                                        <div class="flex items-center gap-3">
                                                            @if($userList->profile_photo)
                                                                <img src="{{ $userList->profile_photo_url }}" alt="{{ $userList->name }}" class="w-[42px] h-[42px] rounded-full object-cover shrink-0">
                                                            @else
                                                                <div class="w-[42px] h-[42px] rounded-full bg-yellow-100 flex items-center justify-center text-xs font-bold text-yellow-700 uppercase shrink-0">
                                                                    {{ substr($userList->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            <div class="min-w-0">
                                                                <p class="text-gray-800 font-semibold text-sm truncate">{{ $userList->name }}</p>
                                                                <p class="text-[10px] text-gray-400 font-bold tracking-widest truncate">{{ $userList->username }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Stepper -->
                            @php
                                $steps = [
                                    ['label' => 'Proyek Dimulai', 'key' => 'dimulai'],
                                    ['label' => 'Dokumen Diverifikasi', 'key' => 'dokumen'],
                                    ['label' => 'Fisik Diverifikasi', 'key' => 'fisik'],
                                    ['label' => 'Properti Dinilai', 'key' => 'dinilai'],
                                    ['label' => 'Proyek Selesai', 'key' => 'selesai'],
                                ];
                                $currentPhase = $proyek->current_phase ?? 'dimulai';
                                $phaseOrder = ['dimulai', 'dokumen', 'fisik', 'dinilai', 'selesai'];
                                $currentIdx = array_search($currentPhase, $phaseOrder);
                                if ($currentIdx === false) $currentIdx = 0;
                            @endphp
                            <div class="mt-6 border border-gray-100 rounded-[30px] p-6">
                                <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Progress Proyek
                                </h3>
                                <div class="flex items-start">
                                    @foreach($steps as $idx => $step)
                                        @php
                                            $isCompleted = $idx < $currentIdx;
                                            $isCurrent = $idx === $currentIdx;
                                            $isFuture = $idx > $currentIdx;
                                        @endphp
                                        <div class="flex-1 flex flex-col items-center relative">
                                            @if($idx > 0)
                                                <div class="absolute top-[18px] right-1/2 w-full h-0.5 -z-10 {{ $idx <= $currentIdx ? 'bg-[#82C17D]' : 'bg-gray-200' }}"></div>
                                            @endif
                                            @if($idx < count($steps) - 1)
                                                <div class="absolute top-[18px] left-1/2 w-full h-0.5 -z-10 {{ $isCompleted ? 'bg-[#82C17D]' : 'bg-gray-200' }}"></div>
                                            @endif
                                            <div class="relative z-10 flex items-center justify-center w-9 h-9 rounded-full border-2 transition-all duration-300
                                                {{ $isCompleted ? 'bg-[#82C17D] border-[#82C17D] shadow-md shadow-[#82C17D]/20' : '' }}
                                                {{ $isCurrent ? 'bg-white border-[#82C17D] shadow-md shadow-[#82C17D]/15 ring-4 ring-[#82C17D]/10' : '' }}
                                                {{ $isFuture ? 'bg-white border-gray-200' : '' }}
                                            ">
                                                @if($isCompleted)
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @elseif($isCurrent)
                                                    <span class="text-sm font-bold text-[#82C17D]">{{ $idx + 1 }}</span>
                                                @else
                                                    <span class="text-xs font-medium text-gray-400">{{ $idx + 1 }}</span>
                                                @endif
                                            </div>
                                            <span class="mt-3 text-[11px] font-semibold text-center leading-tight max-w-[90px]
                                                {{ $isCompleted ? 'text-[#82C17D]' : '' }}
                                                {{ $isCurrent ? 'text-gray-800' : '' }}
                                                {{ $isFuture ? 'text-gray-400' : '' }}
                                            ">{{ $step['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border border-gray-100 rounded-[30px] p-6 mt-6 bg-gray-50/30">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Dibuat Oleh
                                </h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 uppercase">
                                        {{ substr($proyek->creator?->name ?? '-', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-gray-800 font-bold text-sm">{{ $proyek->creator?->name ?? '-' }}</p>
                                        <p class="text-gray-400 text-xs">{{ $proyek->creator?->email ?? '-' }}</p>

                        @elseif($activeMenu === 'dokumen')
                            <!-- DOKUMEN CONTENT -->
                            @if(!$proyek->properti)
                                <div class="bg-red-50 border border-red-100 text-red-700 p-6 rounded-[30px] text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <h3 class="font-bold text-lg">Properti Tidak Ditemukan</h3>
                                    <p class="text-sm opacity-80">Wadah properti untuk proyek ini belum terinisialisasi. Silakan hubungi Admin.</p>
                                </div>
                            @else
                                @php
                                    $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
                                    $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
                                    $canProceed = \App\Services\DocumentRequirementService::canProceed($proyek->properti);
                                    $verifiedTypes = $proyek->properti->dokumens->where('status', 'terverifikasi')->pluck('tipe_dokumen')->toArray();
                                @endphp

                                <div class="flex justify-between items-start mb-8">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800">Dokumen Properti</h2>
                                        <p class="text-gray-500 text-sm mt-1">Tipe Objek: <span class="font-bold text-[#82C17D]">{{ $typeReqs['name'] ?? 'Tidak Diketahui' }}</span></p>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(auth()->user()->isAdmin())
                                            <button onclick="document.getElementById('typeModal').classList.remove('hidden')"
                                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-xs font-bold transition">
                                                ⚙️ Ubah Tipe
                                            </button>
                                        @endif
                                        @if(auth()->user()->role === 'client' || auth()->user()->role === 'karyawan')
                                            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                                                class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-md transition flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Unggah Dokumen
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Gating Warning -->
                                @if(!$canProceed)
                                    <div class="bg-yellow-50 border border-yellow-100 text-yellow-800 p-4 rounded-[20px] mb-8 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div>
                                            <p class="text-sm font-bold">Verifikasi Fisik Terkunci</p>
                                            <p class="text-xs opacity-80">Beberapa dokumen wajib belum diunggah atau belum diverifikasi oleh Karyawan.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-green-50 border border-green-100 text-green-800 p-4 rounded-[20px] mb-8 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <p class="text-sm font-bold">Dokumen Lengkap</p>
                                            <p class="text-xs opacity-80">Proyek siap dilanjutkan ke Fase 2: Verifikasi Fisik.</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Document Checklist — merged single column -->
                                @php
                                    $allMandatory = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
                                @endphp
                                <div class="mb-8">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-1.5 h-6 rounded-full bg-[#82C17D]"></div>
                                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Dokumen Wajib</h3>
                                        <span class="ml-auto text-xs text-gray-400 font-medium">{{ count(array_intersect(array_keys($allMandatory), $verifiedTypes)) }} / {{ count($allMandatory) }} terpenuhi</span>
                                    </div>
                                    <div class="bg-gray-50/70 rounded-[20px] p-5 space-y-1">
                                        @foreach($allMandatory as $key => $label)
                                            @php $isVerified = in_array($key, $verifiedTypes); @endphp
                                            <label class="flex items-center gap-3 py-2.5 px-3 rounded-[12px] transition cursor-default {{ $isVerified ? 'bg-green-50/80' : 'hover:bg-gray-100/60' }}">
                                                <span class="relative flex items-center justify-center w-5 h-5 shrink-0">
                                                    @if($isVerified)
                                                        <span class="w-5 h-5 rounded-md bg-[#82C17D] flex items-center justify-center shadow-sm">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        </span>
                                                    @else
                                                        <span class="w-5 h-5 rounded-md border-2 border-gray-300 bg-white"></span>
                                                    @endif
                                                </span>
                                                <span class="text-sm {{ $isVerified ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Documents List -->
                                <div class="space-y-4">
                                    @forelse($proyek->properti->dokumens as $dokumen)
                                        <div class="border border-gray-100 rounded-[20px] p-5 hover:border-[#82C17D]/30 transition group">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-[12px] bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#82C17D]/5 group-hover:text-[#82C17D] transition">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-800">{{ $dokumen->nama_dokumen }}</h4>
                                                        <p class="text-xs text-gray-500">
                                                            Uploaded by: {{ $dokumen->uploader?->name }} • {{ $dokumen->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                        @if($dokumen->status === 'menunggu') bg-yellow-100 text-yellow-700
                                                        @elseif($dokumen->status === 'terverifikasi') bg-green-100 text-green-700
                                                        @else bg-red-100 text-red-700
                                                        @endif">
                                                        {{ $dokumen->status }}
                                                    </span>

                                                    <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank"
                                                        class="p-2 text-gray-400 hover:text-[#82C17D] hover:bg-gray-50 rounded-full transition" title="Lihat Dokumen">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </a>

                                                    @if(auth()->user()->role === 'karyawan' && $dokumen->status === 'menunggu')
                                                        <button onclick="openVerifyModal({{ $dokumen->id }}, '{{ $dokumen->nama_dokumen }}')"
                                                            class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-full transition" title="Verifikasi">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </button>
                                                    @endif

                                                    @if($dokumen->uploaded_by === auth()->id() && $dokumen->status === 'menunggu')
                                                        <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Hapus">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($dokumen->catatan)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-[12px] text-xs text-gray-600 border-l-2 border-gray-200">
                                                    <strong>Catatan:</strong> {{ $dokumen->catatan }}
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="bg-gray-50 rounded-[20px] p-12 text-center text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-sm italic">Belum ada dokumen yang diunggah.</p>
                                        </div>
                                    @endforelse
                                </div>
                            @endif

                        @elseif($activeMenu === 'fisik')
                            <!-- FISIK CONTENT -->
                            @php
                                $checklistWajib = $proyek->properti->checklistFisiks ?? collect();
                                $allAspekFisik = $proyek->properti->aspekFisiks ?? collect();
                                $wajibFilled = $checklistWajib->filter(fn($c) => $c->verificationStatus() !== 'belum')->count();
                                $wajibTotal = $checklistWajib->count();
                                $allWajibVerified = $wajibTotal > 0 && $checklistWajib->every(fn($c) => $c->verificationStatus() === 'terverifikasi');
                                $opsionalAspeks = $allAspekFisik->whereNull('checklist_fisik_id');
                                $user = auth()->user();
                                $canManage = $user->isKaryawan();
                                $canFill = $user->isKaryawan() || $user->isMitra();
                                $canVerify = $user->isKaryawan();
                            @endphp

                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Fisik Properti</h2>
                                    <p class="text-gray-500 text-sm mt-1">Kelola aspek fisik properti untuk proyek ini.</p>
                                </div>
                                @if($canFill)
                                    <button onclick="openAddModal()" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-md transition flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Aspek Fisik
                                    </button>
                                @endif
                            </div>

                            <!-- Status Banner (matching Dokumen style exactly) -->
                            @if($allWajibVerified)
                                <div class="bg-green-50 border border-green-100 text-green-800 p-4 rounded-[20px] mb-8 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-sm font-bold">Aspek Fisik Lengkap</p>
                                        <p class="text-xs opacity-80">Proyek siap dilanjutkan ke Fase 3: Penilaian.</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-100 text-yellow-800 p-4 rounded-[20px] mb-8 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <div>
                                        <p class="text-sm font-bold">Aspek Fisik Belum Lengkap</p>
                                        <p class="text-xs opacity-80">Beberapa aspek fisik wajib belum diverifikasi.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Aspek Fisik Wajib Checklist (matching Dokumen Wajib style) -->
                            <div class="mb-8">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1.5 h-6 rounded-full bg-[#82C17D]"></div>
                                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Aspek Fisik Wajib</h3>
                                    <span class="ml-auto text-xs text-gray-400 font-medium">{{ $checklistWajib->filter(fn($c) => $c->verificationStatus() === 'terverifikasi')->count() }} / {{ $wajibTotal }} terpenuhi</span>
                                </div>
                                <div class="bg-gray-50/70 rounded-[20px] p-5 space-y-1">
                                    @forelse($checklistWajib as $item)
                                        @php
                                            $vStatus = $item->verificationStatus();
                                            $isVerified = $vStatus === 'terverifikasi';
                                            $isRejected = $vStatus === 'ditolak';
                                            $isPending = $vStatus === 'menunggu';
                                            $isBelum = $vStatus === 'belum';
                                            $latestAspek = $item->aspekFisiks()->latest()->first();
                                        @endphp
                                        <label class="flex items-center gap-3 py-2.5 px-3 rounded-[12px] transition cursor-default {{ $isVerified ? 'bg-green-50/80' : 'hover:bg-gray-100/60' }}">
                                            <span class="relative flex items-center justify-center w-5 h-5 shrink-0">
                                                @if($isVerified)
                                                    <span class="w-5 h-5 rounded-md bg-[#82C17D] flex items-center justify-center shadow-sm">
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </span>
                                                @elseif($isRejected)
                                                    <span class="w-5 h-5 rounded-md border-2 border-red-400 bg-red-50 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </span>
                                                @else
                                                    <span class="w-5 h-5 rounded-md border-2 border-gray-300 bg-white"></span>
                                                @endif
                                            </span>
                                            <span class="text-sm {{ $isVerified ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">{{ $item->nama_item }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400 italic text-center py-4">Belum ada checklist. Karyawan dapat menambahkan checklist aspek fisik wajib.</p>
                                    @endforelse
                                </div>
                                @if($canManage)
                                    <div class="mt-3">
                                        <button type="button" onclick="openChecklistModal()" class="px-4 py-2 bg-[#82C17D] hover:bg-[#6fa86a] text-white rounded-full text-sm font-bold transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Checklist
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Aspek Fisik List (matching Documents List style) -->
                            <div class="space-y-4">
                                @forelse($allAspekFisik->sortByDesc('created_at') as $aspek)
                                    @php
                                        $statusColors = [
                                            'menunggu' => 'bg-yellow-100 text-yellow-700',
                                            'terverifikasi' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                        ];
                                        $isWajib = $aspek->checklist_fisik_id !== null;
                                        $tipeLabel = $isWajib ? 'Wajib' : 'Opsional';
                                    @endphp
                                    <div class="border border-gray-100 rounded-[20px] p-5 hover:border-[#82C17D]/30 transition group">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                @if($aspek->foto_paths && count($aspek->foto_paths) > 0)
                                                    <img src="{{ asset('storage/' . $aspek->foto_paths[0]) }}" alt="{{ $aspek->nama_aspek }}" class="w-12 h-12 rounded-[12px] object-cover shrink-0 cursor-pointer" onclick="openPhotoModal('{{ addslashes($aspek->nama_aspek) }}', {{ json_encode(array_map(fn($p) => asset('storage/' . $p), $aspek->foto_paths)) }})">
                                                @else
                                                    <div class="w-12 h-12 rounded-[12px] bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#82C17D]/5 group-hover:text-[#82C17D] transition">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <h4 class="font-bold text-gray-800">{{ $aspek->nama_aspek }}</h4>
                                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $isWajib ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-600' }}">{{ $tipeLabel }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        Oleh: {{ $aspek->creator?->name ?? '-' }} • {{ $aspek->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$aspek->status] }}">{{ $aspek->status }}</span>

                                                @if($aspek->latitude && $aspek->longitude)
                                                    <a href="https://www.openstreetmap.org/?mlat={{ $aspek->latitude }}&mlon={{ $aspek->longitude }}#map=17/{{ $aspek->latitude }}/{{ $aspek->longitude }}" target="_blank" class="p-2 text-gray-400 hover:text-[#82C17D] hover:bg-gray-50 rounded-full transition" title="Lihat di Peta">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                                    </a>
                                                @endif

                                                @if($canVerify && $aspek->status === 'menunggu')
                                                    <button onclick="openVerifyModal({{ $aspek->id }}, '{{ addslashes($aspek->nama_aspek) }}')" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-full transition" title="Verifikasi">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </button>
                                                @endif
                                                @if($canVerify && $aspek->status !== 'menunggu')
                                                    <button onclick="openVerifyModal({{ $aspek->id }}, '{{ addslashes($aspek->nama_aspek) }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-full transition" title="Ubah Status">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                @endif

                                                @if(($user->isKaryawan() || ($user->isMitra() && $aspek->created_by === $user->id)) && $aspek->status === 'menunggu')
                                                    <button onclick="openEditAspekModal({{ $aspek->id }}, '{{ addslashes($aspek->nama_aspek) }}', '{{ addslashes($aspek->deskripsi ?? '') }}', '{{ $isWajib ? 'wajib' : 'opsional' }}', {{ $aspek->latitude ?? 'null' }}, {{ $aspek->longitude ?? 'null' }}, {{ json_encode($aspek->foto_paths ?? []) }}, {{ $aspek->checklist_fisik_id ?? 'null' }})" class="p-2 text-gray-400 hover:text-[#82C17D] hover:bg-gray-50 rounded-full transition" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    </button>
                                                @endif

                                                @if($user->isKaryawan())
                                                    <form action="{{ route('aspek-fisik.destroy', $aspek->id) }}" method="POST" onsubmit="return confirm('Hapus aspek ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                        @if($aspek->deskripsi)
                                            <div class="mt-3 p-3 bg-gray-50 rounded-[12px] text-xs text-gray-600 border-l-2 border-gray-200">
                                                {{ $aspek->deskripsi }}
                                            </div>
                                        @endif
                                        @if($aspek->catatan)
                                            <div class="mt-2 p-3 bg-gray-50 rounded-[12px] text-xs text-gray-600 border-l-2 border-gray-200">
                                                <strong>Catatan:</strong> {{ $aspek->catatan }}
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="bg-gray-50 rounded-[20px] p-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        <p class="text-sm italic">Belum ada aspek fisik yang ditambahkan.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Peta Properti -->
                            @php
                                $mappedAspeks = $allAspekFisik->filter(fn($a) => $a->latitude && $a->longitude);
                            @endphp
                            @if($mappedAspeks->count() > 0)
                                <div class="border border-gray-100 rounded-[20px] p-6 mt-6">
                                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        Peta Properti
                                    </h3>
                                    <div id="petaProperti" class="w-full h-[350px] rounded-[16px] overflow-hidden border border-gray-200 z-0"></div>
                                </div>
                            @endif

                            <!-- CHECKLIST ADD MODAL -->
                            <div id="checklistModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
                                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="font-bold text-xl text-gray-800" id="checklistModalTitle">Tambah Checklist</h3>
                                        <button onclick="closeChecklistModal()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form action="{{ route('checklist-fisik.store', $proyek->properti->id) }}" method="POST" class="p-8 space-y-5" id="checklistForm">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Item Checklist</label>
                                            <input type="text" name="nama_item" id="checklistNamaInput" required placeholder="Contoh: Kondisi Atap" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">
                                            <input type="hidden" name="tipe" id="checklistTipeInput" value="wajib">
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                                                Tambah Checklist
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- ADD ASPEK MODAL (matching Upload Dokumen style) -->
                            <div id="addModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
                                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="font-bold text-xl text-gray-800">Tambah Aspek Fisik</h3>
                                        <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form id="addForm" action="{{ route('aspek-fisik.store', $proyek->properti->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Jenis Aspek</label>
                                            <select name="checklist_fisik_id" id="addJenisSelect" required onchange="updateAddFormFromSelect(this)"
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm font-medium text-gray-700">
                                                <option value="" disabled selected>Pilih Jenis Aspek</option>
                                                @php $hasUnfilledWajib = $checklistWajib->contains(fn($c) => $c->verificationStatus() === 'belum'); @endphp
                                                @if($hasUnfilledWajib)
                                                <optgroup label="Wajib">
                                                    @foreach($checklistWajib as $item)
                                                        @if($item->verificationStatus() === 'belum')
                                                            <option value="{{ $item->id }}" data-nama="{{ $item->nama_item }}">{{ $item->nama_item }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                                @endif
                                                <optgroup label="Opsional">
                                                    <option value="" data-nama="" data-tipe="opsional">Aspek Opsional</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Aspek</label>
                                            <input type="text" name="nama_aspek" id="addNamaInput" placeholder="Contoh: Lantai Granit" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">
                                            <input type="hidden" name="tipe" id="addTipeInput" value="wajib">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi</label>
                                            <textarea name="deskripsi" id="addDeskripsi" rows="3" placeholder="Deskripsi detail aspek fisik..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Foto (JPG/PNG)</label>
                                            <input type="file" name="foto[]" multiple required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#82C17D] file:text-white hover:file:bg-[#6FA86A] cursor-pointer">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Lokasi (Peta Aspek)</label>
                                            <div id="petaAspek" class="w-full h-[200px] rounded-[12px] overflow-hidden border border-gray-200 z-0 mb-2"></div>
                                            <div class="flex gap-3">
                                                <div class="flex-1">
                                                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Latitude</label>
                                                    <input type="text" name="latitude" id="inputLatitude" required step="any" placeholder="-6.xxxxxx" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2 px-3 text-sm">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Longitude</label>
                                                    <input type="text" name="longitude" id="inputLongitude" required step="any" placeholder="106.xxxxxx" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2 px-3 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                                                Tambah Aspek Fisik
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- EDIT ASPEK MODAL -->
                            <div id="editAspekModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
                                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="font-bold text-xl text-gray-800">Edit Aspek Fisik</h3>
                                        <button onclick="closeEditAspekModal()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form id="editAspekForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="checklist_fisik_id" id="editAspekChecklistId">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Aspek</label>
                                            <input type="text" name="nama_aspek" id="editAspekNama" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi</label>
                                            <textarea name="deskripsi" id="editAspekDeskripsi" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Foto Baru (kosongkan jika tidak diubah)</label>
                                            <input type="file" name="foto[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#82C17D] file:text-white hover:file:bg-[#6FA86A] cursor-pointer">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Lokasi (Peta Aspek)</label>
                                            <div id="petaAspekEdit" class="w-full h-[200px] rounded-[12px] overflow-hidden border border-gray-200 z-0 mb-2"></div>
                                            <div class="flex gap-3">
                                                <div class="flex-1">
                                                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Latitude</label>
                                                    <input type="text" name="latitude" id="editAspekLat" required step="any" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2 px-3 text-sm">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Longitude</label>
                                                    <input type="text" name="longitude" id="editAspekLng" required step="any" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2 px-3 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- VERIFY MODAL -->
                            <div id="verifyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
                                    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="font-bold text-xl text-gray-800">Verifikasi Aspek Fisik</h3>
                                        <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <form id="verifyForm" method="POST" class="p-8 space-y-4">
                                        @csrf
                                        <div class="text-sm text-gray-600 mb-2">
                                            Aspek: <span id="verifyAspekName" class="font-bold text-gray-800"></span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Status Verifikasi</label>
                                            <select name="status" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">
                                                <option value="terverifikasi">✅ Terverifikasi</option>
                                                <option value="ditolak">❌ Ditolak</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                                            <textarea name="catatan" rows="3" placeholder="Berikan catatan verifikasi..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm"></textarea>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                                                Simpan Verifikasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- PHOTO VIEWER MODAL -->
                            <div id="photoModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                                <div class="relative max-w-3xl w-full">
                                    <button onclick="closePhotoModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <h4 id="photoModalTitle" class="text-white font-bold text-lg mb-3 text-center"></h4>
                                    <div id="photoModalContent" class="flex gap-3 overflow-x-auto pb-2 justify-center"></div>
                                </div>
                            </div>

                        @elseif($activeMenu === 'nilai')
                            <!-- NILAI CONTENT -->
                            @php
                                $nilaiData = $proyek->properti?->nilai;
                                $user = auth()->user();
                                $isKaryawan = $user->isKaryawan();

                                // Compute verification status inline for the view
                                $dokumenLengkap = false;
                                $missingDokumen = [];
                                $fisikLengkap = false;
                                $missingFisik = [];

                                if ($proyek->properti) {
                                    // --- Dokumen check ---
                                    $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
                                    $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
                                    if ($typeReqs) {
                                        $allMandatory = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
                                        $verifiedTypes = $proyek->properti->dokumens->where('status', 'terverifikasi')->pluck('tipe_dokumen')->toArray();
                                        foreach ($allMandatory as $key => $label) {
                                            if (!in_array($key, $verifiedTypes)) {
                                                $uploaded = $proyek->properti->dokumens->where('tipe_dokumen', $key)->where('status', 'menunggu')->isNotEmpty();
                                                $missingDokumen[] = $uploaded ? "{$label} (menunggu verifikasi)" : "{$label} (belum diunggah)";
                                            }
                                        }
                                        $dokumenLengkap = empty($missingDokumen);
                                    } else {
                                        $missingDokumen[] = 'Tipe properti tidak dikenali';
                                    }

                                    // --- Fisik check ---
                                    $checklistWajib = $proyek->properti->checklistFisiks;
                                    if ($checklistWajib->isNotEmpty()) {
                                        foreach ($checklistWajib as $item) {
                                            $vStatus = $item->verificationStatus();
                                            if ($vStatus !== 'terverifikasi') {
                                                $statusLabel = $vStatus === 'belum' ? 'belum diisi' : ($vStatus === 'menunggu' ? 'menunggu verifikasi' : 'ditolak');
                                                $missingFisik[] = "{$item->nama_item} ({$statusLabel})";
                                            }
                                        }
                                        $fisikLengkap = empty($missingFisik);
                                    } else {
                                        $missingFisik[] = 'Belum ada checklist fisik. Karyawan harus menambahkan checklist aspek fisik wajib.';
                                    }
                                }

                                $verifikasiLengkap = $dokumenLengkap && $fisikLengkap && $proyek->properti;
                            @endphp

                            <h2 class="text-xl font-bold text-gray-800 mb-6">Penilaian Properti</h2>

                            @if(!$proyek->properti)
                                <div class="bg-red-50 border border-red-100 text-red-700 p-6 rounded-[30px] text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <h3 class="font-bold text-lg">Properti Tidak Ditemukan</h3>
                                    <p class="text-sm opacity-80">Wadah properti untuk proyek ini belum terinisialisasi.</p>
                                </div>
                            @else

                                {{-- ========== VERIFICATION STATUS BANNER ========== --}}
                                @if($verifikasiLengkap)
                                    <div class="bg-green-50 border border-green-100 text-green-800 p-4 rounded-[20px] mb-6 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <p class="text-sm font-bold">Verifikasi Lengkap — Siap Dinilai</p>
                                            <p class="text-xs opacity-80">Semua dokumen dan aspek fisik wajib telah terverifikasi.</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-100 text-yellow-800 p-4 rounded-[20px] mb-6 flex items-start gap-3">
                                        <svg class="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div>
                                            <p class="text-sm font-bold">Penilaian Terkunci</p>
                                            <p class="text-xs opacity-80 mb-2">Verifikasi dokumen dan fisik harus diselesaikan sebelum penilaian dapat dilakukan.</p>

                                            @if(!$dokumenLengkap)
                                                <div class="mt-2">
                                                    <p class="text-xs font-bold text-yellow-700 mb-1">Dokumen yang belum lengkap:</p>
                                                    <ul class="list-disc list-inside text-xs space-y-0.5 ml-1">
                                                        @foreach($missingDokumen as $md)
                                                            <li>{{ $md }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(!$fisikLengkap)
                                                <div class="mt-2">
                                                    <p class="text-xs font-bold text-yellow-700 mb-1">Fisik yang belum lengkap:</p>
                                                    <ul class="list-disc list-inside text-xs space-y-0.5 ml-1">
                                                        @foreach($missingFisik as $mf)
                                                            <li>{{ $mf }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- ========== EXISTING NILAI (View for all roles) ========== --}}
                                @if($nilaiData)
                                    <div class="border border-gray-100 rounded-[20px] p-6 mb-6">
                                        <div class="flex items-center gap-2 mb-4">
                                            <div class="w-1.5 h-6 rounded-full bg-[#82C17D]"></div>
                                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Hasil Penilaian</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-1">Nilai Properti</label>
                                                <p class="text-2xl font-bold text-[#82C17D]">Rp {{ number_format($nilaiData->nilai, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest block mb-1">Dinilai Oleh</label>
                                                <p class="text-sm font-semibold text-gray-800">{{ $nilaiData->creator?->name ?? '-' }}</p>
                                                <p class="text-xs text-gray-400">{{ $nilaiData->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                        @if($nilaiData->catatan)
                                            <div class="mt-4 p-3 bg-gray-50 rounded-[12px] text-sm text-gray-600 border-l-2 border-[#82C17D]">
                                                <strong>Catatan:</strong> {{ $nilaiData->catatan }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- ========== FORM PENILAIAAN (Karyawan only, only if verifikasi lengkap) ========== --}}
                                @if($isKaryawan && $verifikasiLengkap)
                                    <div class="border border-gray-100 rounded-[20px] p-6">
                                        <div class="flex items-center gap-2 mb-5">
                                            <div class="w-1.5 h-6 rounded-full bg-[#82C17D]"></div>
                                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">{{ $nilaiData ? 'Edit Penilaian' : 'Berikan Penilaian' }}</h3>
                                        </div>

                                        <form action="{{ route('properti.nilai.save', $proyek->properti->id) }}" method="POST" class="space-y-5">
                                            @csrf

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Nilai Properti (Rp)</label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                                    <input type="number" name="nilai" id="nilaiInput" min="0" step="1" required
                                                        value="{{ old('nilai', $nilaiData->nilai ?? '') }}"
                                                        placeholder="0"
                                                        class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 pl-12 pr-4 text-sm font-semibold text-gray-800">
                                                </div>
                                                @error('nilai')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Catatan Penilaian</label>
                                                <textarea name="catatan" id="catatanInput" rows="4" placeholder="Berikan catatan/alasan penilaian..."
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">{{ old('catatan', $nilaiData->catatan ?? '') }}</textarea>
                                                @error('catatan')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex gap-3 pt-2">
                                                <button type="submit" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-3 rounded-full text-sm font-bold shadow-md transition flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    {{ $nilaiData ? 'Update Penilaian' : 'Simpan Penilaian' }}
                                                </button>

                                                @if($nilaiData)
                                                    <button type="button" onclick="if(confirm('Hapus penilaian ini?')) document.getElementById('hapusNilaiForm').submit();"
                                                        class="bg-red-50 hover:bg-red-100 text-red-600 px-5 py-3 rounded-full text-sm font-bold transition flex items-center gap-2 border border-red-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </form>

                                        @if($nilaiData)
                                            <form id="hapusNilaiForm" action="{{ route('properti.nilai.destroy', $nilaiData->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>

                                @elseif(!$isKaryawan && !$nilaiData)
                                    {{-- ========== EMPTY STATE FOR NON-KARYAWAN ========== --}}
                                    <div class="bg-gray-50 rounded-[20px] p-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        <p class="text-sm italic">Penilaian belum tersedia.</p>
                                    </div>
                                @endif

                            @endif

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- UPLOAD MODAL -->
    @if($proyek->properti)
    @php
        $typeReqs = \App\Services\DocumentRequirementService::getTypeRequirements($proyek->properti->tipe_properti);
        $globalReqs = \App\Services\DocumentRequirementService::getGlobalRequirements();
        $globalOpts = \App\Services\DocumentRequirementService::getGlobalOptionalRequirements();
        $allMandatory = array_merge($globalReqs, $typeReqs['mandatory'] ?? []);
        $allOptional = array_merge($typeReqs['optional'] ?? [], $globalOpts);
    @endphp
    <div id="uploadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-xl text-gray-800">Tambah Dokumen</h3>
                <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('dokumen.store', $proyek->properti->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Jenis Dokumen</label>
                    <select name="tipe_dokumen" required onchange="updateDocName(this)"
                        class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm font-medium text-gray-700">
                        <option value="" disabled selected>Pilih Jenis Dokumen</option>
                        <optgroup label="Wajib">
                            @foreach($allMandatory as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        @if(count($allOptional) > 0)
                        <optgroup label="Opsional">
                            @foreach($allOptional as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Dokumen / File</label>
                    <input type="text" id="doc_name_input" name="nama_dokumen" placeholder="Contoh: SHM No. 123" required
                        class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D] py-2.5 px-4 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Pilih File (PDF/JPG/PNG)</label>
                    <input type="file" name="file" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#82C17D] file:text-white hover:file:bg-[#6FA86A] cursor-pointer">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                        Unggah Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TYPE MODAL -->
    <div id="typeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-xl text-gray-800">Ubah Tipe Properti</h3>
                <button onclick="document.getElementById('typeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('properti.updateType', $proyek->properti->id) }}" method="POST" class="p-8 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Tipe Objek</label>
                    <select name="tipe_properti" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                        @foreach(\App\Services\DocumentRequirementService::getAllTypes() as $val => $label)
                            <option value="{{ $val }}" {{ $proyek->properti->tipe_properti === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- VERIFY MODAL -->
    <div id="verifyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-md shadow-2xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-xl text-gray-800">Verifikasi Dokumen</h3>
                <button onclick="document.getElementById('verifyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="verifyForm" method="POST" class="p-8 space-y-4">
                @csrf
                <div class="text-sm text-gray-600 mb-2">
                    Dokumen: <span id="verifyDocName" class="font-bold text-gray-800"></span>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Verifikasi</label>
                    <select name="status" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                        <option value="terverifikasi">✅ Terverifikasi (Asli)</option>
                        <option value="ditolak">❌ Ditolak (Palsu/Tidak Lengkap)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Berikan alasan jika ditolak..."
                        class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                        Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ===== DOCUMENT FUNCTIONS =====
        function updateDocName(select) {
            const input = document.getElementById('doc_name_input');
            const selectedText = select.options[select.selectedIndex].text;
            if (input && selectedText) {
                input.value = selectedText;
            }
        }

        function openVerifyModal(id, name) {
            const modal = document.getElementById('verifyModal');
            const form = document.getElementById('verifyForm');
            const nameSpan = document.getElementById('verifyDocName');

            nameSpan.innerText = name;
            form.action = `/dokumen/${id}/verifikasi`;
            modal.classList.remove('hidden');
        }

        // ===== LEAFLET MAPS =====
        let petaProperti = null;
        let petaAspek = null;
        let petaAspekEdit = null;
        let addMarker = null;
        let editAspekMarker = null;

        function getStatusColor(status) {
            return status === 'terverifikasi' ? '#82C17D' : status === 'ditolak' ? '#ef4444' : '#eab308';
        }

        // Initialize Peta Properti (shows all verified aspects with GPS)
        function initPetaProperti() {
            const mapEl = document.getElementById('petaProperti');
            if (!mapEl) return;

            let center = [-6.2, 106.8];
            let zoom = 13;

            const aspeks = [];
            @if($activeMenu === 'fisik')
                @foreach($allAspekFisik as $aspek)
                    @if($aspek->latitude && $aspek->longitude)
                        aspeks.push({
                            lat: {{ $aspek->latitude }},
                            lng: {{ $aspek->longitude }},
                            name: '{{ addslashes($aspek->nama_aspek) }}',
                            status: '{{ $aspek->status }}',
                            tipe: '{{ $aspek->checklist_fisik_id ? 'wajib' : 'opsional' }}',
                            deskripsi: '{{ addslashes($aspek->deskripsi ?? '') }}',
                            foto: '{{ $aspek->foto_paths && count($aspek->foto_paths) > 0 ? asset('storage/' . $aspek->foto_paths[0]) : '' }}'
                        });
                    @endif
                @endforeach
            @endif

            if (aspeks.length > 0) {
                center = [aspeks[0].lat, aspeks[0].lng];
                zoom = 16;
            }

            petaProperti = L.map('petaProperti').setView(center, zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(petaProperti);

            const bounds = [];
            aspeks.forEach(a => {
                const color = getStatusColor(a.status);
                const marker = L.circleMarker([a.lat, a.lng], {
                    radius: 10,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(petaProperti);

                let popup = `<div style="min-width:150px"><strong>${a.name}</strong><br><span style="font-size:11px;color:${color};font-weight:bold">${a.status.toUpperCase()}</span><br><span style="font-size:10px;color:#888">${a.tipe === 'wajib' ? '🔴 Wajib' : '🟡 Opsional'}</span>`;
                if (a.deskripsi) popup += `<br><span style="font-size:11px;color:#555">${a.deskripsi.substring(0,80)}${a.deskripsi.length > 80 ? '...' : ''}</span>`;
                if (a.foto) popup += `<br><img src="${a.foto}" style="width:120px;height:80px;object-fit:cover;border-radius:6px;margin-top:4px">`;
                popup += `</div>`;
                marker.bindPopup(popup);
                bounds.push([a.lat, a.lng]);
            });

            if (bounds.length > 1) {
                petaProperti.fitBounds(bounds, { padding: [30, 30] });
            }
        }

        // Initialize Peta Aspekt (draggable pin for add/edit forms)
        function initPetaAspekt(mapId, latInputId, lngInputId) {
            const mapEl = document.getElementById(mapId);
            if (!mapEl) return;

            const latInput = document.getElementById(latInputId);
            const lngInput = document.getElementById(lngInputId);

            let center = [-6.2, 106.8];
            let zoom = 15;

            if (latInput.value && lngInput.value) {
                center = [parseFloat(latInput.value), parseFloat(lngInput.value)];
                zoom = 17;
            }

            const map = L.map(mapId).setView(center, zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker(center, { draggable: true }).addTo(map);

            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                latInput.value = pos.lat.toFixed(7);
                lngInput.value = pos.lng.toFixed(7);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                latInput.value = e.latlng.lat.toFixed(7);
                lngInput.value = e.latlng.lng.toFixed(7);
            });

            function updateMarkerFromInputs() {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.panTo([lat, lng]);
                }
            }
            latInput.addEventListener('change', updateMarkerFromInputs);
            lngInput.addEventListener('change', updateMarkerFromInputs);

            if (mapId === 'petaAspek') {
                petaAspek = map;
                addMarker = marker;
            } else {
                petaAspekEdit = map;
                editAspekMarker = marker;
            }

            setTimeout(() => map.invalidateSize(), 200);
        }

        // ===== CHECKLIST MODAL =====
        function openChecklistModal() {
            document.getElementById('checklistTipeInput').value = 'wajib';
            document.getElementById('checklistModalTitle').innerText = 'Tambah Checklist';
            document.getElementById('checklistForm').action = `/properti/{{ $proyek->properti->id }}/checklist-fisik`;
            document.getElementById('checklistNamaInput').value = '';
            document.getElementById('checklistModal').classList.remove('hidden');
        }

        function closeChecklistModal() {
            document.getElementById('checklistModal').classList.add('hidden');
        }

        // ===== ADD / FILL ASPEK MODAL =====
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            setTimeout(() => initPetaAspekt('petaAspek', 'inputLatitude', 'inputLongitude'), 300);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            if (petaAspek) { petaAspek.remove(); petaAspek = null; }
            document.getElementById('inputLatitude').value = '';
            document.getElementById('inputLongitude').value = '';
        }

        function openFillModal(checklistId, namaItem) {
            const select = document.getElementById('addJenisSelect');
            select.value = checklistId;
            updateAddFormFromSelect(select);
            openAddModal();
        }

        function updateAddFormFromSelect(select) {
            const option = select.options[select.selectedIndex];
            if (option && option.value) {
                // Wajib item selected
                document.getElementById('addNamaInput').value = option.dataset.nama || '';
                document.getElementById('addTipeInput').value = 'wajib';
            } else if (option) {
                // Opsional selected (empty value)
                document.getElementById('addNamaInput').value = '';
                document.getElementById('addTipeInput').value = 'opsional';
            }
        }

        // ===== EDIT ASPEK MODAL =====
        function openEditAspekModal(id, nama, deskripsi, tipe, lat, lng, fotoPaths, checklistId) {
            const modal = document.getElementById('editAspekModal');
            const form = document.getElementById('editAspekForm');

            form.action = `/aspek-fisik/${id}`;
            document.getElementById('editAspekChecklistId').value = checklistId || '';
            document.getElementById('editAspekNama').value = nama;
            document.getElementById('editAspekDeskripsi').value = deskripsi || '';
            document.getElementById('editAspekLat').value = lat || '';
            document.getElementById('editAspekLng').value = lng || '';

            modal.classList.remove('hidden');
            setTimeout(() => initPetaAspekt('petaAspekEdit', 'editAspekLat', 'editAspekLng'), 300);
        }

        function closeEditAspekModal() {
            document.getElementById('editAspekModal').classList.add('hidden');
            if (petaAspekEdit) { petaAspekEdit.remove(); petaAspekEdit = null; }
        }

        // ===== VERIFY MODAL =====
        function openVerifyModal(id, name) {
            const modal = document.getElementById('verifyModal');
            const form = document.getElementById('verifyForm');

            document.getElementById('verifyAspekName').innerText = name;
            form.action = `/aspek-fisik/${id}/verifikasi`;
            modal.classList.remove('hidden');
        }

        function closeVerifyModal() {
            document.getElementById('verifyModal').classList.add('hidden');
        }

        // ===== PHOTO MODAL =====
        function openPhotoModal(title, photos) {
            document.getElementById('photoModalTitle').innerText = title;
            const content = document.getElementById('photoModalContent');
            content.innerHTML = photos.map(p => `<img src="${p}" class="h-[300px] w-auto rounded-[12px] object-cover shrink-0">`).join('');
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        // Close modals on backdrop click
        ['addModal', 'editAspekModal', 'verifyModal', 'photoModal', 'checklistModal'].forEach(id => {
            document.getElementById(id)?.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (id === 'addModal') closeAddModal();
                    else if (id === 'editAspekModal') closeEditAspekModal();
                    else if (id === 'verifyModal') closeVerifyModal();
                    else if (id === 'photoModal') closePhotoModal();
                    else if (id === 'checklistModal') closeChecklistModal();
                }
            });
        });

        // Initialize Peta Properti on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if($activeMenu === 'fisik')
                initPetaProperti();
            @endif
        });
    </script>
    @endpush
</x-app-layout>
