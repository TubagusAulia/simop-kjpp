<x-app-layout>
    <!-- Add Tribute.js CSS -->
    <link rel="stylesheet" href="https://zurb.github.io/tribute/dist/tribute.css">
    <style>
        .tribute-container { border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: none; overflow: hidden; }
        .tribute-container ul { background: white; }
        .tribute-container li { padding: 10px 15px; border-bottom: 1px solid #f8fafc; font-size: 13px; }
        .tribute-container li.highlight { background: #82C17D; color: white; }
        .mention-tag { background: #eef7ed; color: #15803d; padding: 2px 8px; border-radius: 6px; font-weight: bold; border: 1px solid #82C17D/20; }
    </style>

    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <!-- Back link -->
            <div class="mb-6">
                <a href="{{ route('proyek.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Batal dan Kembali
                </a>
            </div>

            <div class="bg-white rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex items-center gap-4 bg-gray-50/50">
                    <div class="w-12 h-12 bg-[#82C17D] rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Buat Proyek Baru</h1>
                        <p class="text-sm text-gray-500">Inisialisasi kontrak penilaian properti baru.</p>
                    </div>
                </div>

                <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <!-- Section: Identitas Proyek -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-4 h-px bg-gray-200"></span> Identitas Proyek
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Proyek / Kontrak</label>
                                <input type="text" name="nama_proyek" required placeholder="Contoh: Penilaian Aset Ruko PT Maju Jaya"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                                <x-input-error :messages="$errors->get('nama_proyek')" class="mt-1" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Tipe Properti</label>
                                    <select name="tipe_properti" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                                        <option value="" disabled selected>Pilih Tipe...</option>
                                        @foreach(\App\Services\DocumentRequirementService::getAllTypes() as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Unggah Berkas Kontrak (.pdf)</label>
                                    <input type="file" name="kontrak_file" required
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Lingkup Pekerjaan</label>
                                <textarea name="deskripsi" rows="2" placeholder="Jelaskan ringkasan pekerjaan ini..."
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Jadwal & Waktu -->
                    <div class="space-y-4 pt-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-4 h-px bg-gray-200"></span> Jadwal & Waktu
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deadline Selesai</label>
                                <input type="date" name="due_date" required
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#82C17D] focus:ring-[#82C17D]">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Alokasi Tim & Klien via @Mention -->
                    <div class="space-y-4 pt-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-4 h-px bg-gray-200"></span> Alokasi Tim & Klien
                            </h3>
                            <span class="text-[10px] text-gray-400">Ketik @ untuk memanggil user</span>
                        </div>
                        <div class="relative">
                            <div id="mention-container" class="min-h-[120px] p-4 rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50 focus-within:border-[#82C17D] focus-within:bg-white transition-all cursor-text" onclick="document.getElementById('mention-input').focus()">
                                <div id="tags-area" class="flex flex-wrap gap-2 mb-2"></div>
                                <input type="text" id="mention-input" autocomplete="off" placeholder="Ketik @lalu cari nama (misal: @anton)..."
                                    class="w-full border-none bg-transparent focus:ring-0 p-0 text-sm placeholder:text-gray-400">
                            </div>
                            <!-- Hidden inputs for form submission -->
                            <div id="hidden-user-ids"></div>
                        </div>
                        <p class="text-[11px] text-gray-400 italic">* Anda bisa menambahkan banyak Karyawan, Klien, dan Mitra sekaligus.</p>
                        <x-input-error :messages="$errors->get('user_ids')" class="mt-1" />
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6fa86a] text-white py-4 rounded-full font-bold shadow-lg shadow-[#82C17D]/20 transition flex items-center justify-center gap-2">
                            <span>Konfirmasi dan Buat Proyek</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://zurb.github.io/tribute/dist/tribute.js"></script>
    <script>
        const selectedUsers = new Set();
        const tagsArea = document.getElementById('tags-area');
        const hiddenArea = document.getElementById('hidden-user-ids');
        const mentionInput = document.getElementById('mention-input');

        const tribute = new Tribute({
            trigger: '@',
            values: function (text, cb) {
                fetch(`/users/search?q=${text}`)
                    .then(res => res.json())
                    .then(data => cb(data))
                    .catch(() => cb([]));
            },
            selectTemplate: function (item) {
                addUserTag(item.original);
                return ''; // Don't insert text into input, we use tags instead
            },
            menuItemTemplate: function (item) {
                const roleColors = {
                    'karyawan': 'text-blue-600 bg-blue-50',
                    'client': 'text-green-600 bg-green-50',
                    'mitra': 'text-yellow-600 bg-yellow-50'
                };
                const colorClass = roleColors[item.original.role] || 'text-gray-600 bg-gray-50';
                
                return `
                    <div class="flex items-center justify-between w-full gap-8">
                        <div class="flex flex-col">
                            <span class="font-bold">${item.original.name}</span>
                            <span class="text-[10px] text-gray-400">@${item.original.username}</span>
                        </div>
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase ${colorClass}">${item.original.role}</span>
                    </div>
                `;
            },
            lookup: 'name',
            fillAttr: 'username'
        });

        tribute.attach(mentionInput);

        function addUserTag(user) {
            if (selectedUsers.has(user.id)) return;
            
            selectedUsers.add(user.id);
            
            const tag = document.createElement('div');
            tag.className = 'mention-tag inline-flex items-center gap-2';
            tag.innerHTML = `
                <span>@${user.username} (${user.name})</span>
                <button type="button" onclick="removeUserTag(${user.id}, this)" class="hover:text-red-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            tagsArea.appendChild(tag);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'user_ids[]';
            hidden.value = user.id;
            hidden.id = `user-input-${user.id}`;
            hiddenArea.appendChild(hidden);

            mentionInput.value = '';
            mentionInput.placeholder = '';
        }

        function removeUserTag(userId, btn) {
            selectedUsers.delete(userId);
            btn.parentElement.remove();
            document.getElementById(`user-input-${userId}`).remove();
            if (selectedUsers.size === 0) {
                mentionInput.placeholder = 'Ketik @lalu cari nama (misal: @anton)...';
            }
        }
    </script>
    @endpush
</x-app-layout>
