@extends('layouts.VideosAppLayout')

@section('content')
    @php
        $user      = auth()->user();
        $isManager = $user && $user->can('manage-series');
        $owns      = $user && $serie->user_name === $user->name;
        $showBtns  = $isManager || $owns;
        // Ara el redirect és sempre la ruta de show d'aquesta sèrie
        $redirect  = urlencode(route('series.show', $serie->id));
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Banner --}}
        <div class="relative w-full max-w-4xl mx-auto my-6">
            <img src="{{ $serie->image }}" alt="{{ $serie->title }}"
                 class="w-full h-48 object-cover rounded-lg shadow">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex justify-between items-start p-6 rounded-lg">
                <h1 class="text-3xl font-bold text-white">{{ $serie->title }}</h1>
                @if($showBtns)
                    <div class="flex space-x-4">
                        <a href="{{ route('series.manage.edit', $serie->id) }}?redirect={{ $redirect }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Editar
                        </a>
                        <a href="{{ route('series.manage.delete', $serie->id) }}?redirect={{ $redirect }}"
                           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Eliminar
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Creator info --}}
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg mb-8 flex items-center space-x-4">
            @if($serie->user_photo_url)
                <img src="{{ $serie->user_photo_url }}" alt="{{ $serie->user_name }}"
                     class="w-16 h-16 rounded-full border-2 border-white">
            @endif
            <div class="text-white">
                <p class="font-semibold">Creador: {{ $serie->user_name }}</p>
                <p class="text-sm text-gray-400">
                    Publicat el: {{ \Carbon\Carbon::parse($serie->published_at)->format('d/m/Y') }}
                </p>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg mb-8">
            <h2 class="text-2xl font-bold text-white mb-4">Descripció</h2>
            <p class="text-gray-300">{{ $serie->description }}</p>
        </div>

        {{-- Videos grid --}}
        <div>
            <h2 class="text-2xl font-bold text-white mb-4">Episodis disponibles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($serie->videos as $video)
                    <a href="{{ route('video.show', $video->id) }}"
                       class="bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition">
                        @php
                            preg_match('/embed\/([^?]+)/', $video->url, $m);
                            $vid = $m[1] ?? null;
                        @endphp
                        @if($vid)
                            <img src="https://img.youtube.com/vi/{{ $vid }}/mqdefault.jpg"
                                 class="w-full h-40 object-cover" alt="{{ $video->title }}">
                        @else
                            <div class="bg-gray-600 h-40 flex items-center justify-center">
                                <span class="text-white">No imatge</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-white">{{ $video->title }}</h3>
                            <p class="text-gray-400 text-sm mt-2">
                                {{ \Illuminate\Support\Str::limit($video->description, 60) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400">No hi ha vídeos disponibles en aquesta sèrie.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
