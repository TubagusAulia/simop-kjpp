<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#82C17D] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#6fa86a] focus:bg-[#6fa86a] active:bg-[#5c9a57] focus:outline-none focus:ring-2 focus:ring-[#82C17D] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
