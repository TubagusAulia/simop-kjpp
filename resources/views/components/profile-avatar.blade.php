@props(['user', 'size' => 'md'])

@php
    $sizeClasses = [
        'xs'  => 'w-6 h-6 text-[8px]',
        'sm'  => 'w-8 h-8 text-[10px]',
        'md'  => 'w-10 h-10 text-xs',
        'lg'  => 'w-12 h-12 text-sm',
        'xl'  => 'w-[42px] h-[42px] text-xs',
        '2xl' => 'w-16 h-16 text-base',
    ];
    $s = $sizeClasses[$size] ?? $sizeClasses['md'];
    $roleColor = match($user->role) {
        'admin'    => 'bg-purple-100 text-purple-700',
        'karyawan' => 'bg-blue-100 text-blue-700',
        'client'   => 'bg-green-100 text-green-700',
        'mitra'    => 'bg-yellow-100 text-yellow-700',
        default    => 'bg-gray-100 text-gray-600',
    };
@endphp

@if($user->profile_photo)
    <img src="{{ $user->profile_photo_url }}"
        class="{{ $s }} rounded-full object-cover shrink-0"
        alt="{{ $user->name }}">
@else
    <div class="{{ $s }} rounded-full {{ $roleColor }} flex items-center justify-center font-bold uppercase shrink-0">
        {{ substr($user->name, 0, 1) }}
    </div>
@endif
