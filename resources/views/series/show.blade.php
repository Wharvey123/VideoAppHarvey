@php use Illuminate\Support\Str; @endphp
@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Banner de la sèrie: imatge limitada en amplada -->
        <div class="relative w-full max-w-4xl mx-auto my-6">
            <img src="{{ $serie->image }}" alt="{{ $serie->title }}" class="w-full h-48 object-cover rounded-lg shadow">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center rounded-lg">
                <h1 class="text-3xl font-bold text-white text-center px-4">{{ $serie->title }}</h1>
            </div>
        </div>

        <!-- Botó per afegir un nou vídeo a aquesta sèrie -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('videos.manage.create', ['series_id' => $serie->id]) }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                Crear Video
            </a>
        </div>

        <!-- Informació del creador -->
        <div
            class="bg-gray-800 rounded-lg p-6 shadow-lg flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex items-center">
                @if($serie->user_photo_url)
                    <img src="{{ $serie->user_photo_url }}" alt="{{ $serie->user_name }}"
                         class="w-16 h-16 rounded-full mr-4 border-2 border-white">
                @else
                    <span class="text-gray-500">Sense foto</span>
                @endif
                <div class="text-white">
                    <p class="text-xl font-semibold">Creador: {{ $serie->user_name }}</p>
                    <p class="text-sm text-gray-300">Publicat
                        el: {{ \Carbon\Carbon::parse($serie->published_at)->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Secció de descripció -->
        <div class="pt-6 pb-4 border-b border-gray-700 mb-8">
            <h2 class="text-2xl font-bold text-white mb-4">Descripció</h2>
            <p class="text-gray-300 leading-relaxed">{{ $serie->description }}</p>
        </div>

        <!-- Secció d'episodis -->
        <div class="pt-6">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <svg class="h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M15 10l4.5-2.3a1 1 0 0 1 1.5.9v6.8a1 1 0 0 1-1.5.9L15 14M5 18h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Episodis disponibles
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($serie->videos as $video)
                    <a href="{{ route('video.show', $video->id) }}"
                       class="bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                        @php
                            preg_match('/embed\/([^?]+)/', $video->url, $matches);
                            $videoId = $matches[1] ?? null;
                        @endphp
                        @if($videoId)
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="{{ $video->title }}"
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="bg-gray-600 h-40 flex items-center justify-center">
                                <span class="text-white">No imatge</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-white">{{ $video->title }}</h3>
                            <p class="mt-2 text-gray-400 text-sm">{{ Str::limit($video->description, 60) }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400">No hi ha vídeos disponibles en aquesta sèrie.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
