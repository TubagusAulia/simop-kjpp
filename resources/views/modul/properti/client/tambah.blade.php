<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-8 text-[32px] font-poppins font-bold text-gray-800">Tambah Project</h1>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <form method="POST" action="{{ route('client.projects.store') }}" enctype="multipart/form-data" class="space-y-6" id="projectForm">
                    @csrf

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Nama Project</label>
                        <input type="text" name="nama_project" required value="{{ old('nama_project') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Contract Date</label>
                        <input type="date" name="contract_date" required value="{{ old('contract_date') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Contact Person</label>
                        <input type="text" name="contact_person" required value="{{ old('contact_person') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Kategori Project</label>
                        <select name="kategori" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori Project</option>
                            <option value="Contoh Kategori Project 1" {{ old('kategori') == 'Contoh Kategori Project 1' ? 'selected' : '' }}>Contoh Kategori Project 1</option>
                            <option value="Contoh Kategori Project 2" {{ old('kategori') == 'Contoh Kategori Project 2' ? 'selected' : '' }}>Contoh Kategori Project 2</option>
                            <option value="Contoh Kategori Project 3" {{ old('kategori') == 'Contoh Kategori Project 3' ? 'selected' : '' }}>Contoh Kategori Project 3</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="font-medium text-gray-700">
                                Upload Dokumen <span class="text-xs text-red-500 font-bold ml-1">(Wajib PDF, Maks. 5MB)</span>
                            </label>
                            <button type="button" id="addDocumentBtn" class="text-sm text-green-600 hover:text-green-700 font-medium">
                                + Tambah Dokumen Lain
                            </button>
                        </div>

                        <div id="documentContainer" class="space-y-3">
                            <div class="document-row flex flex-col sm:flex-row gap-3 items-start sm:items-center bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                                <select name="document_categories[]" required
                                    class="w-full sm:w-1/3 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                                    <option value="" disabled selected>Pilih Kategori Dokumen</option>
                                    <option value="Contoh Kategori Dokumen A">Contoh Kategori Dokumen A</option>
                                    <option value="Contoh Kategori Dokumen B">Contoh Kategori Dokumen B</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <input type="file" name="documents[]" accept="application/pdf" required class="w-full sm:w-2/3 text-sm text-gray-600 file:mr-4 file:rounded-full file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-green-700 hover:file:bg-green-100">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 text-right">
                        <x-primary-button>Kirim Dokumen</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('documentContainer');
        const addBtn = document.getElementById('addDocumentBtn');

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.document-row');
            rows.forEach((row, i) => {
                let removeBtn = row.querySelector('.remove-doc-btn');
                if (rows.length > 1) {
                    if (!removeBtn) {
                        removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-doc-btn absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm';
                        removeBtn.innerHTML = '&times;';
                        row.style.position = 'relative';
                        row.appendChild(removeBtn);
                    }
                } else if (removeBtn) {
                    removeBtn.remove();
                }
            });
        }

        addBtn.addEventListener('click', function() {
            const firstRow = container.querySelector('.document-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('input[type="file"]').value = '';
            container.appendChild(newRow);
            updateRemoveButtons();
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-doc-btn')) {
                const rows = container.querySelectorAll('.document-row');
                if (rows.length > 1) {
                    e.target.closest('.document-row').remove();
                    updateRemoveButtons();
                }
            }
        });

        container.addEventListener('change', function(e) {
            if (e.target.type === 'file') {
                const file = e.target.files[0];
                if (file) {
                    if (file.type !== 'application/pdf') {
                        alert('Peringatan! Format file ditolak. Pastikan Anda hanya mengunggah dokumen berformat PDF.');
                        e.target.value = '';
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Peringatan! Ukuran file terlalu besar. Batas maksimal untuk satu file adalah 5MB.');
                        e.target.value = '';
                        return;
                    }
                }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
