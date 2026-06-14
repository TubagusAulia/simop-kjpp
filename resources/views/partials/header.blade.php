<nav class="fixed inset-x-0 top-0 bg-[#82C17D] shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}">
                <img src="/images/kjpp_logo.png" alt="Logo" class="h-12 w-auto rounded-[10px]">
            </a>
        </div>

        <div class="hidden md:flex space-x-16 text-white font-medium text-lg">
            <a href="{{ route('proyek.index') }}"
                class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Proyek</a>
            <a href="{{ route('laporan.project') }}"
                class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Laporan</a>
            <a href="{{ route('chats.index') }}"
                class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Obrolan</a>
        </div>

        <div class="relative" id="userDropdown">
            <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-800 hover:text-white focus:outline-none">
                @if (Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                        class="h-10 w-10 rounded-full border-2 border-white object-cover">
                @else
                    <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-[#82C17D] border-2 border-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <div id="dropdownMenu" class="absolute right-0 top-full pt-2 w-48 hidden">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if(Auth::user()->isAdmin()) bg-purple-100 text-purple-700
                            @elseif(Auth::user()->isKaryawan()) bg-blue-100 text-blue-700
                            @elseif(Auth::user()->isClient()) bg-green-100 text-green-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    const menu = document.getElementById('dropdownMenu');
    if (!dropdown.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>
