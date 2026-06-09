<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">
                Properti Saya
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- LEFT MENU -->
                <div class="lg:col-span-1 space-y-6">
                    <a href="{{ route('properti.dokumen') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border {{ request()->routeIs('properti.dokumen') ? 'border-[#82C17D] ring-1 ring-[#82C17D] bg-green-50/30' : 'border-gray-50' }}">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Dokumen</h3>
                                    <p class="text-gray-400 text-sm">Upload & Kelola Berkas</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('properti.fisik') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border {{ request()->routeIs('properti.fisik') ? 'border-[#82C17D] ring-1 ring-[#82C17D] bg-green-50/30' : 'border-gray-50' }}">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Fisik</h3>
                                    <p class="text-gray-400 text-sm">Status Objek Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('properti.penilaian') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border {{ request()->routeIs('properti.penilaian') ? 'border-[#82C17D] ring-1 ring-[#82C17D] bg-green-50/30' : 'border-gray-50' }}">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Penilaian</h3>
                                    <p class="text-gray-400 text-sm">Hasil & Review Penilaian</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- RIGHT CONTENT -->
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] border border-gray-50">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Project Terbaru</h3>
                        <div class="flex items-center gap-3">
                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold uppercase">Aktif</span>
                            <a href="{{ route('client.projects.create') }}"
                                class="inline-flex items-center gap-2 bg-[#82C17D] hover:bg-[#6cad67] text-white text-xs font-bold px-3 py-1 rounded-full transition shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-white">
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 text-[11px] uppercase w-12 text-center">No.</th>
                                    <th class="pb-4 text-[11px] uppercase">Nama Project</th>
                                    <th class="pb-4 text-[11px] uppercase">Status</th>
                                    <th class="pb-4 text-[11px] uppercase text-right">Update Terakhir</th>
                                    <th class="pb-4 text-[11px] uppercase text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse ($projects ?? [] as $project)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition">
                                        <td class="py-5 text-center text-gray-400 text-sm font-medium">{{ $loop->iteration }}</td>
                                        <td class="py-5">
                                            <div class="font-bold text-gray-800 capitalize">{{ $project->nama_project }}</div>
                                        </td>
                                        <td class="py-5">
                                            <x-status-badge :status="$project->status" />
                                        </td>
                                        <td class="py-5 font-semibold text-gray-800 text-right">
                                            {{ $project->updated_at->format('d M, H.i') }}
                                        </td>
                                        <td class="py-5 text-right">
                                            @if(strtolower($project->status ?? '') === 'pending' || strtolower($project->status ?? '') === 'menunggu')
                                                <form action="{{ route('client.projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin membatalkan dan menghapus project ini permanen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 transition p-2 bg-red-50 hover:bg-red-100 rounded-lg shadow-sm" title="Hapus Project">
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
                                        <td colspan="5" class="py-16 text-center text-gray-400 italic">Anda belum memiliki pengajuan project.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
