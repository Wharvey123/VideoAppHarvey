@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4 text-white">Editar Sèrie</h1>
            <form action="{{ route('series.manage.update', $serie->id) }}?redirect={{ urlencode($redirect) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect" value="{{ $redirect }}">

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-white">Títol</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $serie->title) }}"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-white">Descripció</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                              required>{{ old('description', $serie->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-white">Portada (URL)</label>
                    <input type="url" name="image" id="image" value="{{ old('image', $serie->image) }}"
                           class="mt-1 block w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400"
                           required>
                </div>

                <div class="flex justify-between mt-4">
                    <a href="{{ $redirect }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Cancel·lar</a>
                    <button type="submit"><x-button color="blue">Actualitzar Sèrie</x-button></button>
                </div>
            </form>
        </div>
    </div>
@endsection
