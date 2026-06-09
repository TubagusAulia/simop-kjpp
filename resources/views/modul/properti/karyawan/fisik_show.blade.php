<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('properti.fisik') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-[32px] font-poppins font-bold text-gray-800">Detail Elemen Fisik</h1>
            </div>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Elemen</label>
                        <p class="text-lg font-bold text-gray-800">{{ $element->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Deskripsi</label>
                        <p class="text-gray-700">{{ $element->description ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Lokasi</label>
                        <p class="text-gray-700">{{ $element->latitude ?? '-' }}, {{ $element->longitude ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 mb-1">Status</label>
                        <div><x-status-badge :status="$element->status ?? 'pending'" /></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
