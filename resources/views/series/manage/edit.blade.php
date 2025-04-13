@extends('layouts.VideosAppLayout')

@section('content')
    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-white">Editar Sèrie</h1>
        <form action="{{ route('series.manage.update', $serie->id) }}" method="POST">
            @csrf
            @method('PUT')
            @if($serie->image)
                <div class="mb-4 text-center">
                    <img src="{{ $serie->image }}" alt="Portada actual"
                         class="w-32 h-32 object-cover rounded mx-auto mb-2">
                </div>
            @endif
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-white mb-1">Títol</label>
                <input type="text" name="title" id="title" value="{{ $serie->title }}"
                       class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-white mb-1">Descripció</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400" required>{{ $serie->description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-white mb-1">Nova Portada (URL)</label>
                <input type="url" name="image" id="image" value="{{ $serie->image }}"
                       placeholder="https://exemple.com/nova-imatge.jpg"
                       class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400">
            </div>
            <div class="flex justify-end space-x-3 mt-4">
                <a href="{{ route('series.manage.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                    Tornar Enrere
                </a>
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Actualitzar Sèrie
                </button>
            </div>
        </form>
    </div>
@endsection
