@props(['status'])

@php
    $statusBadge = strtolower($status ?? '');
@endphp

<span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
    @if($statusBadge === 'pending' || $statusBadge === 'menunggu')
        bg-yellow-100 text-yellow-700
    @elseif($statusBadge === 'selesai' || $statusBadge === 'approved' || $statusBadge === 'verified')
        bg-green-100 text-green-700
    @elseif($statusBadge === 'rejected' || $statusBadge === 'ditolak')
        bg-red-100 text-red-700
    @else
        bg-gray-100 text-gray-700
    @endif
">
    {{ $status ?? '-' }}
</span>
