<x-guest-layout>
    <div class="hidden md:flex md:w-1/2 items-center justify-center p-12">
        <img src="/images/kjpp_logo.png" alt="KJPP Logo" class="w-72 h-auto object-contain">
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center border-l border-gray-100">

        <div class="mb-8 text-center md:text-left">
             <h2 class="text-3xl font-bold text-gray-800">Log In</h2>
             <p class="text-gray-500 mt-1">SIMOP KJPP Yanuar Rosye dan Rekan</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-text-input id="username"
                    class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="text"
                    name="username"
                    :placeholder="__('Username')"
                    :value="old('username')"
                    required autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div class="relative">
                <x-text-input id="password"
                    class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="password"
                    name="password"
                    :placeholder="__('Password')"
                    required autocomplete="current-password" />

                <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-gray-400">
                    <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4 px-2">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded-full border-gray-300 text-[#86c381] shadow-sm focus:ring-[#86c381]" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember Me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-500 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-8">
                <x-primary-button class="w-full justify-center bg-[#f0f4f8] text-gray-800 hover:bg-[#86c381] hover:text-white py-3 rounded-full transition-all duration-300 shadow-sm border-none">
                    {{ __('Log In') }}
                </x-primary-button>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                Don't have account? <a href="{{ route('register') }}" class="text-[#86c381] font-bold underline">Sign Up</a>
            </div>
        </form>
    </div>

    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
    </script>
</x-guest-layout>
