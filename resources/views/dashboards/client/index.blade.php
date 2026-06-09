<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Selamat Datang,
                {{ Auth::user()->name }}!</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-[15px]">
                <a href="{{ route('properti.client') }}" class="group">
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
                            <p class="text-gray-400 font-medium">{{ $projectCount ?? 0 }} Project Anda</p>
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
                            <p class="text-gray-400 font-medium">Belum ada laporan baru</p>
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
                            <p class="text-gray-400 font-medium">Belum ada pesan</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Project Saya</h3>
                        <a href="{{ route('client.projects.create') }}"
                            class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-4 py-2 rounded-lg text-[13px] font-semibold transition shadow-lg shadow-green-100 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Project</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold w-12 text-center">No</th>
                                    <th class="pb-4 font-semibold">Nama Project</th>
                                    <th class="pb-4 font-semibold">Status</th>
                                    <th class="pb-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($projects ?? [] as $index => $project)
                                    <tr class="border-b last:border-0">
                                        <td class="py-4 font-medium text-gray-400 text-center">{{ $index + 1 }}</td>
                                        <td class="py-4 font-bold text-gray-800 capitalize">{{ $project->nama_project ?? ($project->name ?? 'Project') }}</td>
                                        <td class="py-4">
                                            <x-status-badge :status="$project->status ?? 'pending'" />
                                        </td>
                                        <td class="py-4 text-right">
                                            @if(strtolower($project->status ?? '') === 'pending' || strtolower($project->status ?? '') === 'menunggu')
                                                <form action="{{ route('client.projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus pengajuan project ini secara permanen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition p-2 bg-red-50 hover:bg-red-100 rounded-lg shadow-sm" title="Batalkan & Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-400 italic">Belum ada project.</td>
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
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                                <span class="text-sm">Tidak ada notifikasi baru</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Recent Activity</h3>
                        <div class="space-y-4 text-sm overflow-y-auto max-h-[300px] pr-2">
                            @forelse($projects ?? [] as $project)
                                <div class="flex items-start gap-3 border-b border-gray-50 pb-3 last:border-0">
                                    <div class="bg-green-50 p-2 rounded-full text-[#82C17D] shrink-0 mt-0.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">Menambahkan Project Baru</p>
                                        <p class="text-xs text-gray-400 capitalize truncate mt-0.5">{{ $project->nama_project ?? ($project->name ?? 'Project') }}</p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 shrink-0">{{ $project->created_at->format('d M') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 italic text-center py-4">Belum ada aktivitas</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
