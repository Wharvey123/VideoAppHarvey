@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Detalls de l\'usuari')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">Detalls de l'usuari</h1>

        <!-- Informació de l'usuari -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-white mb-4">{{ $user->name }}</h2>
            <p class="text-gray-400"><span class="font-semibold text-white">Email:</span> {{ $user->email }}</p>

            <!-- Display created_at and updated_at for all users -->
            <p class="text-gray-400"><span class="font-semibold text-white">Creat el:</span> {{ $user->created_at->format('d/m/Y H:i:s') }}</p>
            <p class="text-gray-400"><span class="font-semibold text-white">Actualitzat el:</span> {{ $user->updated_at->format('d/m/Y H:i:s') }}</p>

            <!-- Display Password Reset Link Conditionally -->
            @auth
                @if(auth()->user()->isSuperAdmin() || auth()->user()->id === $user->id)
                    <p class="text-gray-400">
                        <span class="font-semibold text-white">Contrasenya:</span>
                        <a href="{{ route('profile.show') }}" class="text-blue-400 hover:text-blue-300">Canviar contrasenya</a>
                    </p>
                @endif
            @endauth

            <!-- Afegeix més camps segons sigui necessari -->
        </div>

        <!-- Botó per tornar enrere -->
        <div class="mt-6">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Tornar a la llista d'usuaris</a>
        </div>
    </div>
@endsection
