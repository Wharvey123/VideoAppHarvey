@extends('layouts.VideosAppLayout')

@section('content')
    <h1 class="text-5xl text-white mb-4 text-center">Gestió de Sèries</h1>
    <div class="flex justify-center mb-4">
        <a href="{{ route('series.manage.create') }}">
            <x-button color="gray" size="md">Afegir Sèrie</x-button>
        </a>
    </div>

    {{-- Taula desktop (unchanged) --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-lg divide-y divide-gray-200">
            <thead class="bg-gray-800">
            <tr>
                <th class="px-6 py-3 text-left text-white font-semibold">ID</th>
                <th class="px-6 py-3 text-left text-white font-semibold">Nom</th>
                <th class="px-6 py-3 text-center text-white font-semibold">Accions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($series as $serie)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900">{{ $serie->id }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ $serie->title }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('series.manage.edit', $serie->id) }}">
                                <x-button color="blue" size="sm">Editar</x-button>
                            </a>
                            <a href="{{ route('series.manage.delete', $serie->id) }}">
                                <x-button color="red" size="sm">Eliminar</x-button>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hi ha sèries creades encara.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Llista mòbil millorada amb marges --}}
    <div class="sm:hidden px-4">
        <div class="space-y-4">
            @forelse($series as $serie)
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $serie->title }}</h3>
                            <p class="text-sm text-gray-500">ID: {{ $serie->id }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('series.manage.edit', $serie->id) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                            <a href="{{ route('series.manage.delete', $serie->id) }}" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">No hi ha sèries creades encara.</p>
            @endforelse
        </div>
    </div>
@endsection
