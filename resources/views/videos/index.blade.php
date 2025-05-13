@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Inici')

@php
    if (!function_exists('getYoutubeThumbnail')) {
        function getYoutubeThumbnail($url): string
        {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
            return isset($matches[1]) ? 'https://img.youtube.com/vi/' . $matches[1] . '/hqdefault.jpg' : asset('images/placeholder.png');
        }
    }
@endphp

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado reorganizado -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center my-6 gap-4">
            <h1 class="text-3xl font-bold text-white">VideosApp</h1>

            @auth
                @can('videos.create')
                    <div class="w-full sm:w-auto">
                        <a href="{{ route('videos.manage.create') }}"
                           class="block w-full sm:w-auto bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-center">
                            Afegir Nou Vídeo
                        </a>
                    </div>
                @endcan
            @endauth
        </div>

        <!-- Grid de vídeos -->
        @if($videos->isEmpty())
            <p class="text-center text-gray-400 text-lg py-8">No hi ha vídeos disponibles en aquest moment.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($videos as $video)
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                        <a href="{{ route('video.show', $video->id) }}">
                            <img src="{{ $video->thumbnail ?? getYoutubeThumbnail($video->url) }}"
                                 alt="{{ $video->title }}"
                                 class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h2 class="text-xl font-semibold text-white line-clamp-2">{{ $video->title }}</h2>
                                <p class="text-gray-400 text-sm mt-2">
                                    Publicat: {{ $video->formatted_published_at }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
