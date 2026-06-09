@props(['show' => false, 'maxWidth' => '2xl'])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div id="{{ $attributes->get('id') }}" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 {{ $show ? '' : 'hidden' }}">
    <div class="bg-white rounded-[30px] w-full {{ $maxWidth }} shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        @if(isset($header))
        <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center text-white shrink-0">
            <h3 class="font-bold text-lg">{{ $header }}</h3>
            <button onclick="closeModal('{{ $attributes->get('id') }}')" class="hover:bg-white/20 rounded-full p-1 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif

        @if(isset($body))
        <div class="p-6 overflow-y-auto">
            {{ $body }}
        </div>
        @endif

        @if(isset($footer))
        <div class="p-6 pt-0 border-t border-gray-50 mt-auto shrink-0 flex justify-end">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>

<script>
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
</script>
