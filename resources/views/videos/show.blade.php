@extends('layouts.VideosAppLayout')

@section('title', $video->title)

@section('content')
    <!-- Encapçalament -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- Títol del vídeo -->
        <h1 class="text-3xl font-bold text-white">{{ $video->title }}</h1>
        <p class="text-sm text-gray-400 mt-2">
            Publicat: {{ $video->formatted_published_at }}
            <span class="text-gray-500">|</span>
            {{ $video->formatted_for_humans_published_at }}
        </p>
    </div>

    <!-- Contingut del vídeo -->
    <div class="mt-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Iframe per al vídeo de YouTube -->
            <iframe class="w-full"
                    style="height: calc(100vw * 9 / 16); max-height: 500px;"
                    src="{{ $video->url }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Descripció del vídeo -->
    <div class="mt-8 bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-white">Descripció</h2>
        <p class="text-gray-300 mt-4">{{ $video->description }}</p>
    </div>

    <!-- Navegació entre vídeos -->
    <div class="mt-8 flex justify-between items-center text-sm">
        @if($video->previous)
            <a href="{{ url('/video/' . $video->previous) }}"
               class="text-blue-400 hover:text-blue-600 font-medium">
                &larr; Vídeo anterior
            </a>
        @else
            <span class="text-gray-500">No hi ha vídeo anterior</span>
        @endif

        <!-- Enllaç per tornar a la llista -->
        <a href="{{ url('/videos') }}" class="text-blue-400 hover:text-blue-600 font-medium">
            Tornar a la llista
        </a>

        @if($video->next)
            <a href="{{ url('/video/' . $video->next) }}"
               class="text-blue-400 hover:text-blue-600 font-medium">
                Vídeo següent &rarr;
            </a>
        @else
            <span class="text-gray-500">No hi ha vídeo següent</span>
        @endif
    </div>
@endsection
