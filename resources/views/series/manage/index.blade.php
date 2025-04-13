@extends('layouts.VideosAppLayout')

@section('content')
    <h1 class="text-5xl text-white mb-6 text-center">Gestió de Sèries</h1>
    <div class="flex justify-center mb-6">
        <a href="{{ route('series.manage.create') }}" class="bg-gray-800 text-white px-6 py-3 rounded hover:bg-gray-700 transition-all">
            Afegir Sèrie
        </a>
    </div>
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4 px-4 py-6">
            <div class="bg-gray-100 rounded-lg shadow-xl overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-white font-semibold">Portada</th>
                        <th class="px-6 py-3 text-left text-white font-semibold">Nom de la Sèrie</th>
                        <th class="px-6 py-3 text-center text-white font-semibold">Foto Perfil</th>
                        <th class="px-6 py-3 text-left text-white font-semibold">Creador</th>
                        <th class="px-6 py-3 text-center text-white font-semibold">Accions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($series as $serie)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Portada de la sèrie amb imatge lleugerament més gran i rectangular -->
                            <td class="px-6 py-4">
                                @if($serie->image)
                                    <img src="{{ $serie->image }}" alt="Portada de la sèrie" class="w-18 h-12 object-cover rounded">
                                @else
                                    <span class="text-gray-500">Sense imatge</span>
                                @endif
                            </td>
                            <!-- Títol de la sèrie -->
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $serie->title }}</td>
                            <!-- Foto de perfil del creador -->
                            <td class="px-6 py-4 text-center">
                                @if($serie->user_photo_url)
                                    <img src="{{ $serie->user_photo_url }}" alt="Foto perfil" class="w-16 h-16 rounded-full object-cover inline-block">
                                @else
                                    <span class="text-gray-500">Sense foto</span>
                                @endif
                            </td>
                            <!-- Nom d'usuari del creador -->
                            <td class="px-6 py-4 text-gray-900">{{ $serie->user_name }}</td>
                            <!-- Accions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('series.manage.edit', $serie->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Editar</a>
                                    <a href="{{ route('series.manage.delete', $serie->id) }}" class="text-red-600 hover:text-red-800 font-medium">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
