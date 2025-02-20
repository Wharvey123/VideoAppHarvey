@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Inici')

@php
    // Si la funció getYoutubeThumbnail no existeix, la definim
    if (!function_exists('getYoutubeThumbnail')) {
        function getYoutubeThumbnail($url): string
        {
            // Usem una expressió regular per trobar l'ID del vídeo de YouTube a l'URL
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
            // Si trobem un ID de vídeo, retornem l'URL de la miniatura. Si no, retornem una imatge de substitució
            return isset($matches[1]) ? 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg' : asset('images/placeholder.png');
        }
    }
@endphp

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">VideosApp</h1>

        <!-- Grid de vídeos, similar a YouTube -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $video)
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <!-- Enllaç al vídeo -->
                    <a href="{{ route('video.show', $video->id) }}">
                        <!-- Imatge del vídeo, ja sigui la miniatura de YouTube o una imatge de substitució -->
                        <img src="{{ $video->thumbnail ?? getYoutubeThumbnail($video->url) }}" alt="{{ $video->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <!-- Títol del vídeo -->
                            <h2 class="text-xl font-semibold text-white">{{ $video->title }}</h2>
                            <!-- Data de publicació del vídeo -->
                            <p class="text-gray-400 text-sm mt-2">
                                Publicat: {{ $video->formatted_published_at }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
