<x-guest-layout>
    <div class="hidden md:flex md:w-1/2 items-center justify-center p-12">
        <img src="/images/kjpp_logo.png" alt="KJPP Logo" class="w-72 h-auto object-contain">
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center border-l border-gray-100">
        <div class="mb-8 text-center md:text-left">
            <h2 class="text-3xl font-bold text-gray-800">Reset Password</h2>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <x-text-input id="email"
                    class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="password"
                    class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="password"
                    name="password_confirmation"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-8">
                <x-primary-button class="w-full justify-center bg-[#f0f4f8] text-gray-800 hover:bg-[#86c381] hover:text-white py-3 rounded-full transition-all duration-300 shadow-sm border-none">
                    Reset Password
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
