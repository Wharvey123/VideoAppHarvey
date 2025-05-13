@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-white">Afegir Nou Vídeo</h1>
            <form action="{{ route('videos.manage.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirect }}">

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-white">Títol</label>
                    <input type="text" name="title" id="title" placeholder="Introdueix el títol"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-white">Descripció</label>
                    <textarea name="description" id="description" rows="4" placeholder="Introdueix la descripció"
                              class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                              required></textarea>
                </div>

                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-white">URL</label>
                    <input type="url" name="url" id="url" placeholder="https://exemple.com/video"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="mb-4">
                    <label for="series_id" class="block text-sm font-medium text-white">Assigna a una sèrie</label>
                    <select name="series_id" id="series_id"
                            class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400">
                        <option value="">No pertany a una sèrie</option>
                        @foreach($series as $serie)
                            <option value="{{ $serie->id }}"
                                {{ (isset($seriesId) && $seriesId == $serie->id) ? 'selected' : '' }}>
                                {{ $serie->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between mt-4">
                    <a href="{{ $redirect }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                        Tornar Enrere
                    </a>
                    <button type="submit">
                        <x-button color="blue" size="md">Desar Vídeo</x-button>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
