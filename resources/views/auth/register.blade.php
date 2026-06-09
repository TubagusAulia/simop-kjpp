<x-guest-layout>
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#86c381]">Sign Up</h1>
            <p class="text-gray-500 mt-2">SIMOP KJPP Yanuar Rosye dan Rekan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf>

            <div>
                <x-text-input id="name"
                    class="block w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="text" name="name" :placeholder="__('Full Name')"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="email"
                    class="block w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="email" name="email" :placeholder="__('Email Address')"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <select name="role" id="role" class="block w-full bg-blue-50/50 border-none rounded-full px-6 py-3 text-gray-500 focus:ring-[#86c381]">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="mitra" {{ old('role') == 'mitra' ? 'selected' : '' }}>Mitra</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="password"
                    class="block w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="password" name="password" :placeholder="__('Password')"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-text-input id="password_confirmation"
                    class="block w-full bg-blue-50/50 border-none rounded-full px-6 py-3"
                    type="password" name="password_confirmation"
                    :placeholder="__('Confirm Password')" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="pt-4">
                <x-primary-button class="w-full justify-center bg-[#86c381] hover:bg-[#75b071] text-white py-3 rounded-full shadow-lg border-none text-lg">
                    {{ __('Sign Up') }}
                </x-primary-button>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                Already have an account? <a href="{{ route('login') }}" class="text-[#86c381] font-bold underline">Log In</a>
            </div>
        </form>
    </div>

    <div class="hidden md:flex md:w-1/2 bg-[#86c381] items-center justify-center p-12 relative">
        <div class="text-white text-right z-10">
            <img src="/images/kjpp_logo.png" alt="kjpp" class="w-full max-w-sm mx-auto">
        </div>
    </div>
</x-guest-layout>
