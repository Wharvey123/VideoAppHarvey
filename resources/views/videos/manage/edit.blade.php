@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Editar Vídeo')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6 text-center">Editar Vídeo</h1>

        <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <form action="{{ route('videos.manage.update', $video->id) }}?redirect={{ urlencode($redirect) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect" value="{{ $redirect }}">

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-white">Títol</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-white">Descripció</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                              required>{{ old('description', $video->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-white">URL</label>
                    <input type="url" name="url" id="url" value="{{ old('url', $video->url) }}"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="mb-4">
                    <label for="series_id" class="block text-sm font-medium text-white">Assigna a una sèrie</label>
                    <select name="series_id" id="series_id"
                            class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400">
                        <option value="">No pertany a una sèrie</option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}" {{ old('series_id', $video->series_id) == $serie->id ? 'selected' : '' }}>
                                {{ $serie->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botons alineats correctament -->
                <div class="flex justify-between">
                    <!-- Botó de cancel·lació fora del formulari -->
                    <a href="{{ $redirect }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancel·lar
                    </a>

                    <!-- Botó de guardar -->
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Actualitzar Vídeo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
