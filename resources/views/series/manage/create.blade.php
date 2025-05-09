@extends('layouts.VideosAppLayout')

@section('content')
    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-white">Crear Nova Sèrie</h1>
        <form action="{{ route('series.manage.store') }}" method="POST">
            @csrf
            <input type="hidden" name="redirect" value="{{ url()->previous() }}">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-white mb-1">Títol</label>
                <input type="text" name="title" id="title" placeholder="Introdueix el títol"
                       class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-white mb-1">Descripció</label>
                <textarea name="description" id="description" placeholder="Introdueix una descripció" rows="4"
                          class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400" required></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-white mb-1">Portada (URL de la imatge)</label>
                <input type="url" name="image" id="image" placeholder="https://exemple.com/imatge.jpg"
                       class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:border-blue-400" required>
            </div>
            <div class="flex justify-end space-x-3 mt-4">
                <a href="{{ session('series_create_redirect', route('series.index')) }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                    Tornar Enrere
                </a>
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Desar Sèrie
                </button>
            </div>
        </form>
    </div>
@endsection
