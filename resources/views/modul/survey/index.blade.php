<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="bg-[#82C17D] pt-8 pb-20 rounded-b-[40px] shadow-lg relative z-0">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-white">
                <div>
                    <a href="{{ route('dashboard') }}" class="flex items-center text-green-100 hover:text-white mb-2 transition">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-bold font-poppins">Survey: {{ $project->nama_project ?? 'Nama Project' }}</h1>
                    <p class="mt-2 text-green-100/90 text-sm">Input elemen fisik dan lokasi</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 -mt-12 relative z-10">
            <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] min-h-[500px]">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Elemen Fisik</h3>
                    <button onclick="openModal()" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-6 py-2 rounded-full font-bold shadow-md transition-all flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Elemen
                    </button>
                </div>

                <div id="emptyState" class="hidden flex-col items-center justify-center py-12 text-center text-gray-400">
                    <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    <p>Belum ada elemen fisik yang ditambahkan.</p>
                </div>

                <div class="space-y-4">
                    <div class="border border-gray-100 rounded-2xl p-4 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Contoh: Batas Utara</h4>
                                <p class="text-xs text-gray-400">Lat: -6.200000, Long: 106.816666</p>
                            </div>
                        </div>
                        <x-status-badge status="pending" />
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-gray-100 flex justify-end">
                    <button class="bg-gray-800 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-700 transition">Kirim Survey</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH ELEMEN -->
    <div id="elementModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 class="font-bold text-lg">Tambah Elemen Baru</h3>
                <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form id="elementForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Elemen</label>
                        <input type="text" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]" placeholder="Contoh: Batas Utara" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                        <textarea class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]" rows="2" placeholder="Keterangan kondisi fisik..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi (Titik Peta)</label>
                        <p class="text-xs text-gray-400 mb-2">Klik pada peta untuk menandai lokasi.</p>
                        <div id="map" class="w-full h-[250px] rounded-xl z-0 border border-gray-200"></div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-500">Latitude</label>
                                <input type="text" id="latInput" class="w-full text-xs rounded-lg border-gray-200 bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500">Longitude</label>
                                <input type="text" id="longInput" class="w-full text-xs rounded-lg border-gray-200 bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Foto Bukti (Opsional)</label>
                        <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                    </div>
                </form>
            </div>

            <div class="p-6 pt-0 border-t border-gray-50 mt-auto shrink-0 flex justify-end">
                <button type="button" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-8 py-3 rounded-full font-bold shadow-md transition-all mt-4 w-full sm:w-auto">Simpan Elemen</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        let map;
        let marker;

        function openModal() {
            document.getElementById('elementModal').classList.remove('hidden');
            initMap();
        }

        function closeModal() {
            document.getElementById('elementModal').classList.add('hidden');
        }

        function initMap() {
            if (map) {
                map.invalidateSize();
                return;
            }
            const defaultLat = -6.2088;
            const defaultLng = 106.8456;
            map = L.map('map').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                document.getElementById('latInput').value = lat;
                document.getElementById('longInput').value = lng;
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
            });
        }
    </script>
</x-app-layout>
