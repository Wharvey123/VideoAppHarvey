@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-4 text-center">Editar Vídeo</h1>
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <form action="{{ route('videos.manage.update', $video->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Títol</label>
                    <input type="text" name="title" id="title" value="{{ $video->title }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripció</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" required>{{ $video->description }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" name="url" id="url" value="{{ $video->url }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" required>
                </div>
                <div class="mb-4">
                    <label for="published_at" class="block text-sm font-medium text-gray-700">Data de publicació</label>
                    <input type="date" name="published_at" id="published_at" value="{{ $video->published_at ? $video->published_at->format('Y-m-d') : '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                </div>
                <div class="flex justify-between">
                    <a href="{{ route('videos.manage.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Tornar Enrere</a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Actualitzar Vídeo</button>
                </div>
            </form>
        </div>
    </div>
@endsection
