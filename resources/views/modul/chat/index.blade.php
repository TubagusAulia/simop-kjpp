<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100" style="height:85vh;">

                <!-- Left: Users / Search -->
                <div class="w-1/3 border-r bg-white flex flex-col" id="chat-left">
                    <!-- Sidebar Header -->
                    <div class="p-4 bg-gray-50 flex items-center justify-between border-b">
                        <div class="flex items-center gap-3">
                            <img src="{{ Auth::user()->profile_photo_url ?? '/images/profile-user.svg' }}" class="h-10 w-10 rounded-full border border-gray-200" />
                            <div class="font-bold text-gray-700">Pesan Saya</div>
                        </div>
                        <div class="flex gap-4 text-gray-500">
                            <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="p-3">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input id="search-input" type="text" placeholder="Cari atau mulai chat baru" 
                                class="w-full bg-gray-100 border-none rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-1 focus:ring-[#82C17D] focus:bg-white transition-all" 
                                oninput="filterUsers(this.value)" />
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="px-3 pb-2 flex gap-2">
                        <button onclick="setFilter('all')" class="px-4 py-1.5 rounded-full text-xs font-bold bg-[#82C17D] text-white">Semua</button>
                        <button onclick="setFilter('unread')" class="px-4 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 hover:bg-gray-200 transition">Belum Dibaca</button>
                    </div>

                    <!-- User List -->
                    <ul id="chat-users" class="flex-1 overflow-y-auto divide-y divide-gray-50">
                        @forelse($users ?? [] as $user)
                            <li onclick="selectUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->profile_photo_url ?? '/images/profile-user.svg' }}')"
                                class="chat-user flex items-center gap-4 p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                                data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}">
                                <div class="relative">
                                    <img src="{{ $user->profile_photo_url ?? '/images/profile-user.svg' }}" class="h-12 w-12 rounded-full object-cover border border-gray-100" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="font-bold text-gray-900 truncate">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400">12:45</div>
                                    </div>
                                    <div class="text-xs text-gray-500 truncate flex items-center justify-between">
                                        <span class="truncate">Klik untuk memulai obrolan...</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="text-center text-gray-400 mt-10 p-4">Tidak ada kontak ditemukan.</div>
                        @endforelse
                    </ul>
                </div>

                <!-- Right: Conversation -->
                <div class="flex-1 flex flex-col relative bg-[#efe7dd]" id="chat-right-container">
                    <!-- WhatsApp Pattern Overlay -->
                    <div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');"></div>

                    <!-- Empty State -->
                    <div id="chat-empty" class="flex-1 flex items-center justify-center relative z-10">
                        <div class="text-center bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-sm border border-white">
                            <div class="w-20 h-20 bg-[#82C17D]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#82C17D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Pilih Obrolan</h3>
                            <p class="text-gray-500 max-w-xs mx-auto">Mulai percakapan dengan rekan tim, klien, atau mitra Anda.</p>
                        </div>
                    </div>

                    <!-- Active Chat -->
                    <div id="chat-conversation" class="flex-1 flex flex-col hidden relative z-10 h-full">
                        <!-- Chat Header -->
                        <div class="p-3 bg-gray-50 flex items-center justify-between border-b shadow-sm">
                            <div class="flex items-center gap-3">
                                <img id="chat-header-avatar" src="" class="h-10 w-10 rounded-full object-cover border border-gray-200" />
                                <div>
                                    <div id="chat-header-name" class="font-bold text-gray-800 leading-tight"></div>
                                    <div id="chat-header-sub" class="text-[10px] text-gray-400 font-medium truncate max-w-[200px]"></div>
                                </div>
                            </div>
                            <div class="flex gap-5 text-gray-400">
                                <svg class="w-5 h-5 cursor-pointer hover:text-[#82C17D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <svg class="w-5 h-5 cursor-pointer hover:text-[#82C17D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div id="messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-2 scroll-smooth">
                            <!-- Dynamic Messages -->
                        </div>

                        <!-- Chat Input -->
                        <div class="p-3 bg-gray-50 border-t flex items-center gap-2 relative">
                            <button class="p-2 text-gray-500 hover:text-[#82C17D] transition" onclick="toggleAttachMenu()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            </button>

                            <!-- Attachment Menu -->
                            <div id="attachMenu" class="absolute bottom-16 left-4 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 hidden transition-all duration-200">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 py-1 mb-1">Lampiran</div>
                                <label class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 rounded-xl cursor-pointer transition">
                                    <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">Galeri</span>
                                    <input type="file" class="hidden" accept="image/*,video/*" onchange="handleAttachment(this)">
                                </label>
                                <label class="flex items-center gap-3 px-3 py-2 hover:bg-gray-50 rounded-xl cursor-pointer transition">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">Dokumen</span>
                                    <input type="file" class="hidden" accept=".pdf,.doc,.docx" onchange="handleAttachment(this)">
                                </label>
                            </div>

                            <form id="message-form" onsubmit="sendMessage(event)" class="flex-1 flex gap-2">
                                <input id="message-input" type="text" placeholder="Ketik pesan..." 
                                    class="flex-1 bg-white border-none rounded-xl px-4 py-2.5 shadow-sm focus:ring-1 focus:ring-[#82C17D] text-sm" />
                                <button type="submit" class="bg-[#82C17D] hover:bg-[#6ab065] text-white p-2.5 rounded-xl shadow-md transition-all active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env('REVERB_APP_KEY') }}',
            wsHost: '{{ env('REVERB_HOST') }}',
            wsPort: {{ env('REVERB_PORT') }},
            wssPort: {{ env('REVERB_PORT') }},
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        let selectedUserId = null;
        const currentUserId = {{ Auth::id() }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Listen for new messages
        window.Echo.private(`chat.${currentUserId}`)
            .listen('MessageSent', (e) => {
                if (selectedUserId && (e.message.sender_id === selectedUserId)) {
                    appendMessage(e.message);
                    markAsRead(e.message.sender_id);
                }
            });

        function selectUser(id, name, username, avatar) {
            selectedUserId = id;
            document.getElementById('chat-empty').classList.add('hidden');
            document.getElementById('chat-conversation').classList.remove('hidden');
            document.getElementById('chat-header-name').textContent = name;
            document.getElementById('chat-header-sub').textContent = '@' + username;
            document.getElementById('chat-header-avatar').src = avatar;

            document.querySelectorAll('.chat-user').forEach(el => el.classList.remove('bg-gray-100'));
            event.currentTarget.classList.add('bg-gray-100');

            loadMessages();
            markAsRead(id);
        }

        async function loadMessages() {
            if (!selectedUserId) return;
            try {
                const response = await fetch(`/messages/conversation/${selectedUserId}`);
                const messages = await response.json();
                renderMessages(messages);
            } catch (error) { console.error(error); }
        }

        function renderMessages(messages) {
            const container = document.getElementById('messages');
            container.innerHTML = '';
            messages.forEach(m => appendMessage(m, false));
            container.scrollTop = container.scrollHeight;
        }

        function appendMessage(m, scroll = true) {
            const container = document.getElementById('messages');
            const isMine = m.sender_id === currentUserId;
            const time = new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            const div = document.createElement('div');
            div.className = `mb-2 flex ${isMine ? 'justify-end' : 'justify-start'}`;
            div.innerHTML = `
                <div class="relative max-w-[75%] px-3 py-1.5 rounded-xl shadow-sm bubble ${isMine ? 'bubble--me bg-[#dcf8c6]' : 'bubble--other bg-white'}">
                    <div class="text-[14.5px] leading-relaxed text-gray-800 pr-10">${m.body}</div>
                    <div class="absolute bottom-1 right-2 flex items-center gap-1">
                        <span class="text-[9px] text-gray-400 font-medium">${time}</span>
                        ${isMine ? `<span class="text-[10px] ${m.is_read ? 'text-[#34b7f1]' : 'text-gray-300'}">
                            ${m.is_read ? '✔✔' : '✔'}
                        </span>` : ''}
                    </div>
                </div>
            `;
            container.appendChild(div);
            if (scroll) container.scrollTop = container.scrollHeight;
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const body = input.value.trim();
            if (!body || !selectedUserId) return;
            input.value = '';

            try {
                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ recipient_id: selectedUserId, body: body })
                });
                const sentMessage = await response.json();
                appendMessage(sentMessage);
            } catch (error) { console.error(error); }
        }

        function markAsRead(userId) {
            fetch(`/messages/conversation/${userId}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
        }

        function toggleAttachMenu() { document.getElementById('attachMenu').classList.toggle('hidden'); }

        function handleAttachment(input) {
            document.getElementById('attachMenu').classList.add('hidden');
            if (input.files.length > 0) document.getElementById('message-input').value = `[File: ${input.files[0].name}]`;
        }

        function filterUsers(query) {
            const q = query.toLowerCase();
            document.querySelectorAll('.chat-user').forEach(el => {
                const match = el.dataset.name.includes(q) || el.dataset.email.includes(q);
                el.style.display = match ? '' : 'none';
            });
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('attachMenu');
            if (menu && !menu.contains(e.target) && !e.target.closest('button[onclick="toggleAttachMenu()"]')) menu.classList.add('hidden');
        });
    </script>

    <style>
        .bubble { position: relative; }
        .bubble--me::after {
            content: ""; position: absolute; top: 0; right: -8px; width: 0; height: 0;
            border: 10px solid transparent; border-left-color: #dcf8c6; border-top-color: #dcf8c6; border-bottom: 0;
        }
        .bubble--other::after {
            content: ""; position: absolute; top: 0; left: -8px; width: 0; height: 0;
            border: 10px solid transparent; border-right-color: #ffffff; border-top-color: #ffffff; border-bottom: 0;
        }
    </style>
    @endpush
</x-app-layout>
