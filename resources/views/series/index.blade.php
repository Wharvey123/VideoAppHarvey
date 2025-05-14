@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Mostrar sèries')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado: versió original per PC, adaptada per mòbil -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center my-6 gap-4 sm:gap-0">
            <h1 class="text-3xl font-bold text-white">Totes les Sèries</h1>

            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-4 sm:space-x-4">
                <!-- Buscador - versió mòbil (ample complet) -->
                <form method="GET" action="{{ route('series.index') }}" class="w-full sm:w-auto">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Cercar sèries..." value="{{ request('search') }}"
                               class="w-full sm:w-64 px-4 py-2 rounded-l-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>

                @auth
                    <a href="{{ route('series.manage.create') }}"
                       class="w-full sm:w-auto bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-center sm:text-left">
                        Afegir Nova Sèrie
                    </a>
                @endauth
            </div>
        </div>

        <!-- Grid de sèries (igual que l'original) -->
        @if($series->isEmpty())
            <div class="text-center text-white text-lg font-semibold py-8">
                No hi ha sèries disponibles en aquest moment.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($series as $serie)
                    <div class="bg-gray-800 rounded-lg shadow overflow-hidden transform hover:scale-105 transition duration-300">
                        <a href="{{ route('series.show', $serie->id) }}" class="block relative group">
                            <img src="{{ $serie->image }}" alt="{{ $serie->title }}"
                                 class="w-full h-48 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-4 transition-opacity group-hover:bg-opacity-70">
                                <h2 class="text-xl font-semibold text-white">{{ $serie->title }}</h2>
                                <p class="text-gray-300 text-sm mt-1">
                                    {{ $serie->videos_count }} vídeos
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
