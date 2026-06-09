<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-[32px] font-poppins font-bold text-gray-800">Verifikasi Survey: {{ $project->nama_project ?? 'Project' }}</h1>
            </div>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <h3 class="text-xl font-bold mb-6">Elemen Fisik Survey</h3>
                <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr class="text-gray-400 text-sm border-b">
                                <th class="pb-4 font-semibold">Elemen</th>
                                <th class="pb-4 font-semibold">Deskripsi</th>
                                <th class="pb-4 font-semibold">Lokasi</th>
                                <th class="pb-4 font-semibold">Foto</th>
                                <th class="pb-4 font-semibold">Status</th>
                                <th class="pb-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-400 italic">Belum ada elemen survey.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
