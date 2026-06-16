<x-guest-layout>
    <div class="hidden md:flex md:w-1/2 items-center justify-center p-12">
        <img src="/images/kjpp_logo.png" alt="KJPP Logo" class="w-72 h-auto object-contain">
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center border-l border-gray-100">
        <div class="mb-8 text-center md:text-left">
            <h2 class="text-3xl font-bold text-gray-800">Forgot Password</h2>
            <p class="text-gray-500 mt-2">Verify your old credentials and set a new username & password</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="border-b border-gray-200 pb-4 mb-4">
                <p class="text-sm font-semibold text-gray-600 mb-3">Old Credentials</p>

                <div>
                    <x-text-input id="last_username"
                        class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                        type="text"
                        name="last_username"
                        :placeholder="__('Last Username')"
                        :value="old('last_username')"
                        required autofocus />
                    <x-input-error :messages="$errors->get('last_username')" class="mt-2" />
                </div>

                <div class="mt-3">
                    <x-text-input id="last_password"
                        class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                        type="password"
                        name="last_password"
                        :placeholder="__('Last Password')"
                        required />
                    <x-input-error :messages="$errors->get('last_password')" class="mt-2" />
                </div>
            </div>

            <div class="pb-4 mb-4">
                <p class="text-sm font-semibold text-gray-600 mb-3">New Credentials</p>

                <div>
                    <x-text-input id="new_username"
                        class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                        type="text"
                        name="new_username"
                        :placeholder="__('New Username')"
                        :value="old('new_username')"
                        required />
                    <x-input-error :messages="$errors->get('new_username')" class="mt-2" />
                </div>

                <div class="mt-3">
                    <x-text-input id="new_password"
                        class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                        type="password"
                        name="new_password"
                        :placeholder="__('New Password')"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                </div>

                <div class="mt-3">
                    <x-text-input id="new_password_confirmation"
                        class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                        type="password"
                        name="new_password_confirmation"
                        :placeholder="__('Confirm New Password')"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div>
                <textarea name="catatan" id="catatan" rows="2"
                    class="block w-full bg-blue-50/50 border-none rounded-2xl px-6 py-3 text-gray-700 placeholder-gray-400 focus:ring-[#86c381]"
                    placeholder="Catatan untuk Admin" required>{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="mt-8">
                <x-primary-button class="w-full justify-center bg-[#f0f4f8] text-gray-800 hover:bg-[#86c381] hover:text-white py-3 rounded-full transition-all duration-300 shadow-sm border-none">
                    Reset Account
                </x-primary-button>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <a href="{{ route('login') }}" class="text-[#86c381] font-bold underline">Back to Log In</a>
            </div>
        </form>
    </div>
</x-guest-layout>
