<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Account') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
    </header>

    <x-danger-button onclick="if(confirm('Are you sure you want to delete your account?')) document.getElementById('delete-account-form').submit();">
        {{ __('Delete Account') }}
    </x-danger-button>

    <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</section>
