@extends('layouts.VideosAppLayout')

@section('title', $video->title)

@section('content')
    <!-- Encapçalament -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 mx-4 md:mx-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between">
            <!-- Títol i dates -->
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $video->title }}</h1>
                <p class="text-sm text-gray-400 mt-2">
                    Publicat: {{ $video->formatted_published_at }}
                    <span class="text-gray-500">|</span>
                    {{ $video->formatted_for_humans_published_at }}
                </p>
            </div>

            @php
                $user      = auth()->user();
                $canAll    = $user && $user->can('manage-videos');
                $owns      = $user && $video->user_id === $user->id;
                $showButtons = $canAll || $owns;
                $redirect   = urlencode(request()->fullUrl());
            @endphp

            @if($showButtons)
                <div class="flex space-x-4 mt-4 md:mt-1 md:self-start">
                    @if($canAll || $owns)
                        <a href="{{ route('videos.manage.edit', $video->id) }}?redirect={{ $redirect }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Editar
                        </a>
                        <a href="{{ route('videos.manage.delete', $video->id) }}?redirect={{ urlencode(route('videos.index')) }}"
                           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Eliminar
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Contingut del vídeo -->
    <div class="mt-8 mx-4 md:mx-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <iframe class="w-full"
                    style="height: calc(100vw * 9 / 16); max-height: 500px;"
                    src="{{ $video->url }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Descripció -->
    <div class="mt-8 bg-gray-800 rounded-lg shadow-lg p-6 mx-4 md:mx-8">
        <h2 class="text-xl font-semibold text-white">Descripció</h2>
        <p class="text-gray-300 mt-4">{{ $video->description }}</p>
    </div>

    <!-- Navegació -->
    <div class="mt-8 flex justify-between items-center text-sm mx-4 md:mx-8">
        @if($video->previous)
            <a href="{{ url('/video/' . $video->previous) }}"
               class="text-blue-400 hover:text-blue-600 font-medium">
                &larr; Vídeo anterior
            </a>
        @else
            <span class="text-gray-500">No hi ha vídeo anterior</span>
        @endif

        <a href="{{ url('/videos') }}"
           class="text-blue-400 hover:text-blue-600 font-medium">
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
