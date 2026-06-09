<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Selamat Datang, {{ Auth::user()->name }}!</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <h3 class="text-xl font-bold mb-6 font-poppins text-gray-800">Daftar Tugas Mitra</h3>

                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] pr-2">
                        <table class="w-full text-left">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold w-10">No</th>
                                    <th class="pb-4 font-semibold">Nama Project</th>
                                    <th class="pb-4 font-semibold text-center">Aksi</th>
                                    <th class="pb-4 font-semibold text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @if(isset($assignedProjects) && count($assignedProjects) > 0)
                                    @foreach($assignedProjects as $index => $project)
                                        <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors">
                                            <td class="py-4 font-medium">{{ $index + 1 }}</td>
                                            <td class="py-4 font-medium text-gray-800">
                                                {{ $project->nama_project }}
                                                <div class="text-xs text-gray-400 font-normal mt-0.5">Assigned</div>
                                            </td>
                                            <td class="py-4 text-center">
                                                <button onclick="openSurveyModal('{{ $project->nama_project }}', {{ $project->id }})"
                                                   class="inline-flex items-center px-4 py-2 bg-[#82C17D] hover:bg-[#6fa86a] text-white text-xs font-bold rounded-full transition shadow-sm">
                                                    <span>Input Data Fisik</span>
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </button>
                                            </td>
                                            <td class="py-4 text-right">
                                                <x-status-badge :status="$project->status ?? 'pending'" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-8">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <p class="text-sm italic">Belum ada tugas mitra yang tersedia.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Notifikasi</h3>
                        <div class="border-2 border-dashed border-gray-100 rounded-xl p-6 flex flex-col items-center justify-center text-center min-h-[100px]">
                            <svg class="w-6 h-6 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="text-sm text-gray-400">Tidak ada notifikasi baru</span>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Status Project</h3>
                        <div class="space-y-4 text-sm">
                            @if(isset($assignedProjects) && count($assignedProjects) > 0)
                                @foreach($assignedProjects->take(3) as $project)
                                    <div class="flex justify-between items-center border-b border-gray-50 pb-2 last:border-0">
                                        <span class="text-gray-600 font-medium truncate w-24">{{ $project->nama_project }}</span>
                                        <x-status-badge :status="$project->status ?? 'pending'" />
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-400 text-center text-xs">Belum ada data status.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SURVEY MODAL -->
    <div id="surveyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h3 class="font-bold text-xl text-gray-800" id="modalProjectTitle">Input Data Fisik</h3>
                    <p class="text-sm text-gray-400">Kelola elemen fisik dan lokasi objek</p>
                </div>
                <button onclick="closeSurveyModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-8 overflow-y-auto" id="modalContentArea">
                <!-- VIEW 1: LIST ELEMENTS -->
                <div id="viewList">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-bold text-gray-700">Daftar Elemen</h4>
                        <button onclick="switchView('form')" class="bg-[#82C17D] hover:bg-[#6fa86a] text-white px-5 py-2 rounded-full text-sm font-bold shadow-md transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Elemen
                        </button>
                    </div>

                    <div id="emptyState" class="flex flex-col items-center justify-center py-12 border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/50">
                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <p class="text-gray-400 text-sm font-medium">Belum ada elemen fisik yang ditambahkan</p>
                    </div>

                    <div id="elementsContainer" class="space-y-4 mb-8"></div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button onclick="closeSurveyModal()" class="bg-gray-800 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-700 transition">
                            Selesai & Tutup
                        </button>
                    </div>
                </div>

                <!-- VIEW 2: FORM -->
                <div id="viewForm" class="hidden">
                    <button onclick="switchView('list')" class="mb-4 text-gray-400 hover:text-gray-600 text-sm flex items-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Daftar
                    </button>

                    <form id="elementForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Elemen</label>
                            <input type="text" name="name" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]" placeholder="Contoh: Batas Utara" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]" rows="2" placeholder="Keterangan kondisi fisik..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Foto Bukti (Opsional)</label>
                            <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#82C17D] file:text-white hover:file:bg-[#6FA86A]" accept="image/*">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Lokasi (Titik Peta)</label>
                            <div id="map" class="w-full h-[250px] rounded-xl z-0 border border-gray-200"></div>
                            <div class="grid grid-cols-2 gap-4 mt-3">
                                <input type="text" name="latitude" id="latInput" placeholder="Latitude" class="w-full text-xs rounded-lg border-gray-200 bg-gray-100" readonly>
                                <input type="text" name="longitude" id="longInput" placeholder="Longitude" class="w-full text-xs rounded-lg border-gray-200 bg-gray-100" readonly>
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="button" onclick="saveElement()" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-3 rounded-full font-bold shadow-md transition">
                                Simpan Elemen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        let map;
        let marker;
        let currentProjectId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function openSurveyModal(projectName, projectId) {
            currentProjectId = projectId;
            document.getElementById('modalProjectTitle').innerText = 'Data Fisik: ' + projectName;
            document.getElementById('surveyModal').classList.remove('hidden');
            loadElements(projectId);
            switchView('list');
        }

        function closeSurveyModal() {
            document.getElementById('surveyModal').classList.add('hidden');
            currentProjectId = null;
        }

        async function loadElements(projectId) {
            const container = document.getElementById('elementsContainer');
            const emptyState = document.getElementById('emptyState');
            container.innerHTML = '';
            emptyState.classList.add('hidden');

            try {
                const response = await fetch(`/survey/${projectId}/elements`);
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    result.data.forEach(item => {
                        const el = document.createElement('div');
                        el.className = 'bg-white border border-gray-100 p-4 rounded-xl shadow-sm flex items-center justify-between';
                        el.innerHTML = `
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                                    ${item.image_path ? `<img src="/storage/${item.image_path}" class="w-full h-full object-cover">` : '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'}
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-800 text-sm">${item.name}</h5>
                                    <p class="text-xs text-gray-500">Lat: ${parseFloat(item.latitude).toFixed(6)}, Long: ${parseFloat(item.longitude).toFixed(6)}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full uppercase tracking-wide">${item.status}</span>
                        `;
                        container.appendChild(el);
                    });
                } else {
                    emptyState.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading elements:', error);
                container.innerHTML = '<div class="text-red-500 text-sm text-center">Gagal memuat data.</div>';
            }
        }

        function switchView(viewName) {
            const viewList = document.getElementById('viewList');
            const viewForm = document.getElementById('viewForm');

            if (viewName === 'form') {
                viewList.classList.add('hidden');
                viewForm.classList.remove('hidden');
                document.getElementById('elementForm').reset();
                document.getElementById('latInput').value = '';
                document.getElementById('longInput').value = '';
                if (marker) map.removeLayer(marker);
                marker = null;
                initMap();
            } else {
                viewForm.classList.add('hidden');
                viewList.classList.remove('hidden');
            }
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

        async function saveElement() {
            if (!currentProjectId) return;

            const form = document.getElementById('elementForm');
            const formData = new FormData(form);
            formData.append('project_id', currentProjectId);

            if (!formData.get('name') || !formData.get('latitude') || !formData.get('longitude')) {
                alert('Nama dan Lokasi (Klik Peta) wajib diisi!');
                return;
            }

            try {
                const response = await fetch('{{ route("survey.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('Berhasil menyimpan!');
                    switchView('list');
                    loadElements(currentProjectId);
                } else {
                    let errorMsg = 'Gagal menyimpan.';
                    if (result.errors) {
                        errorMsg += '\n' + JSON.stringify(result.errors);
                    } else if (result.message) {
                        errorMsg += '\n' + result.message;
                    }
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error saving:', error);
                alert('Terjadi kesalahan sistem: ' + error.message);
            }
        }
    </script>
</x-app-layout>
