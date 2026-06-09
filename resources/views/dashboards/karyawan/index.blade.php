<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Selamat Datang,
                {{ Auth::user()->name }}!
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-[15px]">
                <a href="{{ route('properti.karyawan') }}" class="group">
                    <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50 group-hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-all cursor-pointer">
                        <div class="bg-[#82C17D] p-3 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Properti</h3>
                            <p class="text-gray-400 font-medium">{{ $stats['properti_count'] ?? 0 }} Project sedang dikelola</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('laporan.project') }}" class="group">
                    <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50 group-hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-all cursor-pointer">
                        <div class="bg-[#82C17D] p-3 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Laporan</h3>
                            <p class="text-gray-400 font-medium">{{ $stats['laporan_count'] ?? 0 }} Laporan harus diselesaikan</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('chats.index') }}" class="group">
                    <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50 group-hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-all cursor-pointer">
                        <div class="bg-[#82C17D] p-3 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Obrolan</h3>
                            <p class="text-gray-400 font-medium">{{ $stats['pesan_count'] ?? 0 }} pesan masuk</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <h3 class="text-xl font-bold mb-6">Daftar Tugas</h3>
                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold">No</th>
                                    <th class="pb-4 font-semibold">Tugas</th>
                                    <th class="pb-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($recentProjects ?? [] as $index => $project)
                                    <tr class="border-b last:border-0">
                                        <td class="py-4 font-medium">{{ $index + 1 }}</td>
                                        <td class="py-4">Verifikasi dokumen Project {{ $project->client->name ?? 'User' }}</td>
                                        <td class="py-4">
                                            <x-status-badge :status="$project->status ?? 'pending'" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-400 italic">Belum ada tugas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Notifikasi</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-2 border-b border-gray-100 pb-3">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                                <span class="text-sm">{{ $stats['pesan_count'] ?? 0 }} pesan masuk</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Project Terbaru</h3>
                        <div class="space-y-4 text-sm overflow-y-auto max-h-[180px] pr-2">
                            @foreach($recentProjects ?? [] as $project)
                                <div class="flex justify-between items-center border-b border-gray-50 pb-2 last:border-0">
                                    <span class="text-gray-600 font-medium truncate w-20">{{ $project->client->name ?? 'User' }}</span>
                                    <span class="text-gray-400 font-bold px-2 text-xs">Project {{ $project->name ?? $project->nama_project }}</span>
                                    <span class="font-semibold text-gray-800">
                                        {{ $project->created_at?->format('H.i') ?? '--.--' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
