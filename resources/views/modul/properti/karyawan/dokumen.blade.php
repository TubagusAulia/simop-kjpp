<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Dokumen Karyawan</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-6">
                    <a href="{{ route('properti.karyawan') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-all cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Kembali</h3>
                                    <p class="text-gray-400 text-sm">Daftar Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Dokumen Project</h3>
                        <form action="{{ route('karyawan.verify-batch') }}" method="POST" id="batchForm">
                            @csrf
                            <button type="submit" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow">
                                Verifikasi Semua
                            </button>
                        </form>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold"><input type="checkbox" id="selectAll"></th>
                                    <th class="pb-4 font-semibold">Nama Dokumen</th>
                                    <th class="pb-4 font-semibold">Kategori</th>
                                    <th class="pb-4 font-semibold">Status</th>
                                    <th class="pb-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400 italic">Belum ada dokumen.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.getElementById('selectAll')?.addEventListener('click', function() {
        document.querySelectorAll('.doc-checkbox').forEach(cb => cb.checked = this.checked);
    });
    </script>
    @endpush
</x-app-layout>
