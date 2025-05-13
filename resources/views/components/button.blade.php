@props([
    'color' => 'blue',
    'size'  => 'md',
])

@php
    $colors = [
        'blue'  => 'bg-blue-500 hover:bg-blue-700 text-white',
        'red'   => 'bg-red-500 hover:bg-red-700 text-white',
        'green' => 'bg-green-500 hover:bg-green-700 text-white',
        'gray'  => 'bg-gray-500 hover:bg-gray-700 text-white',
    ];
    $sizes = [
        'sm' => 'px-2 py-1 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];
@endphp

<button
    {{ $attributes->merge(['class' => "{$colors[$color]} {$sizes[$size]} rounded-lg transition-colors duration-200"]) }}
>
    {{ $slot }}
</button>
