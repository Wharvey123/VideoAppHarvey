@props(['shadow' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-6 ' . ($shadow ? 'shadow-lg' : '')]) }}>
    {{ $slot }}
</div>
