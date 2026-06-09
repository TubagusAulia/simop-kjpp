@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#82C17D] focus:ring-[#82C17D] rounded-md shadow-sm']) !!}>
