<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex bg-white shadow rounded-lg overflow-visible" style="height:80vh;">

                <!-- Left: Users / Search -->
                <div class="w-1/3 border-r p-4 bg-white shadow-lg" id="chat-left">
                    <div class="flex items-center mb-4">
                        <input id="search-input" type="text" placeholder="Pencarian" class="w-full rounded-md border-gray-200 px-3 py-2" oninput="filterUsers(this.value)" />
                    </div>
                    <div class="mb-3 flex gap-2">
                        <button id="filter-all" onclick="setFilter('all')" class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">All</button>
                        <button id="filter-unread" onclick="setFilter('unread')" class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">Unread</button>
                        <button id="filter-important" onclick="setFilter('important')" class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-700">Important</button>
                    </div>

                    <ul id="chat-users" class="space-y-3 overflow-auto h-[calc(80vh-220px)]">
                        @forelse($users ?? [] as $user)
                            <li onclick="selectUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->profile_photo_url ?? '/images/profile-user.png' }}')"
                                class="chat-user flex items-center gap-3 p-2 rounded hover:bg-gray-100 cursor-pointer"
                                data-name="{{ strtolower($user->name) }}"
                                data-email="{{ strtolower($user->email) }}">
                                <img src="{{ $user->profile_photo_url ?? '/images/profile-user.png' }}" class="h-10 w-10 rounded-full" />
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="font-medium truncate">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 ml-2 truncate max-w-[35%] text-right">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="text-center text-gray-400 mt-10">Tidak ada kontak.</div>
                        @endforelse
                    </ul>
                </div>

                <!-- Right: Conversation -->
                <div class="flex-1 p-4 flex flex-col bg-white shadow-lg relative">
                    <div id="chat-empty" class="flex-1 flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-lg font-medium">Siapa yang ingin di chat?</div>
                            <div class="text-sm text-gray-500">Pilih pengguna dari daftar di sebelah kiri untuk memulai percakapan.</div>
                        </div>
                    </div>

                    <div id="chat-conversation" class="flex-1 flex flex-col hidden">
                        <div id="chat-header" class="mb-4">
                            <div id="chat-top-bar" class="bg-gradient-to-l from-[#7CC576] to-white rounded-lg p-4 flex items-center gap-4 shadow-sm transition-colors duration-200">
                                <img id="chat-header-avatar" src="" alt="avatar" class="h-14 w-14 rounded-full border-2 border-white object-cover" />
                                <div>
                                    <h3 id="chat-header-name" class="text-xl font-semibold tracking-wide"></h3>
                                    <div id="chat-header-sub" class="text-sm text-gray-700"></div>
                                </div>
                            </div>
                            <div class="border-b border-gray-200 mt-3"></div>
                        </div>

                        <div id="messages" class="flex-1 overflow-auto px-2 pb-4">
                            <!-- Messages loaded via polling -->
                        </div>

                        <div id="message-form-wrap" class="pt-3 border-t mt-3">
                            <form id="message-form" onsubmit="sendMessage(event)" class="flex gap-2 items-center">
                                <input id="message-input" type="text" placeholder="Ketik pesan di sini..." class="flex-1 rounded-full border-gray-200 px-4 py-3" autocomplete="off" />

                                <div class="relative">
                                    <button type="button" onclick="toggleAttachMenu()" class="ms-2 p-2 text-gray-600 hover:text-green-600 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.44 11.05L12.36 20.13a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24l-9.19 9.19a1 1 0 01-1.41-1.41l9.19-9.19" />
                                        </svg>
                                    </button>

                                    <div id="attachMenu" class="absolute bottom-12 right-0 w-44 bg-white border rounded shadow p-2 z-50 hidden">
                                        <div class="text-sm text-gray-600 mb-2">Pilih lampiran</div>
                                        <div class="space-y-1">
                                            <label class="block px-2 py-1 hover:bg-gray-100 cursor-pointer text-sm">
                                                Foto & Video
                                                <input type="file" class="hidden" accept="image/*,video/*" onchange="handleAttachment(this)">
                                            </label>
                                            <label class="block px-2 py-1 hover:bg-gray-100 cursor-pointer text-sm">
                                                Dokumen
                                                <input type="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" onchange="handleAttachment(this)">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button id="send-button" type="submit" class="ms-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full">➤</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedUserId = null;
        let pollInterval = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function selectUser(id, name, email, avatar) {
            selectedUserId = id;
            document.getElementById('chat-empty').classList.add('hidden');
            document.getElementById('chat-conversation').classList.remove('hidden');
            document.getElementById('chat-header-name').textContent = name;
            document.getElementById('chat-header-sub').textContent = email;
            document.getElementById('chat-header-avatar').src = avatar;

            // Highlight selected user
            document.querySelectorAll('.chat-user').forEach(el => el.classList.remove('bg-gray-50'));
            event.currentTarget.classList.add('bg-gray-50');

            // Load messages immediately
            loadMessages();

            // Start polling every 3 seconds
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(loadMessages, 3000);
        }

        async function loadMessages() {
            if (!selectedUserId) return;

            try {
                const response = await fetch(`/messages/conversation/${selectedUserId}`);
                const messages = await response.json();
                renderMessages(messages);
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        function renderMessages(messages) {
            const container = document.getElementById('messages');
            const currentUserId = {{ Auth::id() }};
            let html = '';

            if (messages.length === 0) {
                html = '<div class="text-center text-gray-400 mt-10">Belum ada pesan.</div>';
            } else {
                messages.forEach(m => {
                    const isMine = m.sender_id === currentUserId;
                    const time = new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                    html += `
                        <div class="mb-4 flex ${isMine ? 'justify-end' : 'justify-start'}">
                            <div class="bubble ${isMine ? 'bubble--me' : 'bubble--other'} text-black p-3 max-w-[60%] relative group">
                                ${m.body ? `<div class="mb-1">${m.body}</div>` : ''}
                                <div class="text-xs text-gray-500 mt-1 flex items-center justify-end">
                                    ${time}
                                    ${isMine ? `<span class="ms-2 ${m.is_read ? 'text-blue-500' : 'text-gray-400'}">${m.is_read ? '✔✔' : '✔'}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        }

        async function sendMessage(e) {
            e.preventDefault();
            if (!selectedUserId) return;

            const input = document.getElementById('message-input');
            const body = input.value.trim();
            if (!body) return;

            input.value = '';

            try {
                const response = await fetch('/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        recipient_id: selectedUserId,
                        body: body
                    })
                });

                if (response.ok) {
                    loadMessages();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        }

        function toggleAttachMenu() {
            const menu = document.getElementById('attachMenu');
            menu.classList.toggle('hidden');
        }

        function handleAttachment(input) {
            document.getElementById('attachMenu').classList.add('hidden');
            if (input.files.length > 0) {
                // For now, just show the file name — full upload can be added later
                const fileName = input.files[0].name;
                document.getElementById('message-input').value = `[File: ${fileName}]`;
            }
        }

        function filterUsers(query) {
            const q = query.toLowerCase();
            document.querySelectorAll('.chat-user').forEach(el => {
                const name = el.dataset.name;
                const email = el.dataset.email;
                if (name.includes(q) || email.includes(q)) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        function setFilter(filter) {
            document.getElementById('filter-all').className = 'px-3 py-1 rounded-full text-sm ' + (filter === 'all' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700');
            document.getElementById('filter-unread').className = 'px-3 py-1 rounded-full text-sm ' + (filter === 'unread' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700');
            document.getElementById('filter-important').className = 'px-3 py-1 rounded-full text-sm ' + (filter === 'important' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700');
        }

        // Close attach menu on outside click
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('attachMenu');
            if (menu && !menu.contains(e.target) && !e.target.closest('button[onclick="toggleAttachMenu()"]')) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <style>
        .bubble { position: relative; border-radius: 12px; box-shadow: 0 6px 18px rgba(16,24,40,0.06); }
        .bubble--me { background-color: #dcfce7; color: #064e3b; }
        .bubble--other { background-color: #ffffff; color: #111827; }
    </style>
    @endpush
</x-app-layout>
