@extends('layouts.VideosAppLayout')

@section('content')
    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-white">Afegir Nou Vídeo</h1>
        <form action="{{ route('videos.manage.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-white">Títol</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded shadow-sm focus:outline-none focus:border-blue-400" data-qa="video-title" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-white">Descripció</label>
                <textarea name="description" id="description" class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded shadow-sm focus:outline-none focus:border-blue-400" data-qa="video-description" required></textarea>
            </div>
            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-white">URL</label>
                <input type="url" name="url" id="url" class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded shadow-sm focus:outline-none focus:border-blue-400" data-qa="video-url" required>
            </div>
            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-white">Data de publicació</label>
                <input type="date" name="published_at" id="published_at" class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded shadow-sm focus:outline-none focus:border-blue-400" data-qa="video-published_at">
            </div>
            {{--
            <div class="mb-4">
                <label for="series_id" class="block text-sm font-medium text-white">Sèrie</label>
                <select name="series_id" id="series_id" class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded shadow-sm focus:outline-none focus:border-blue-400" data-qa="video-series_id" required>
                    @foreach($series as $serie)
                        <option value="{{ $serie->id }}">{{ $serie->name }}</option>
                    @endforeach
                </select>
            </div>
            --}}
            <a href="{{ route('videos.manage.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700" style="width: 150px; display: inline-block; text-align: center;">Tornar Enrere</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700" data-qa="submit-video" style="width: 150px; display: inline-block; text-align: center;">Desar Vídeo</button>
        </form>
    </div>
@endsection
