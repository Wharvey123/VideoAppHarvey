@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Confirmar Eliminació')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">Confirmar Eliminació</h1>

        <!-- Missatge de confirmació amb fons més visible -->
        <div class="bg-gray-800 p-6 rounded-lg mb-6">
            <p class="text-white">
                Estàs segur que vols eliminar l'usuari <strong class="text-red-500">{{ $user->name }}</strong>?
            </p>
        </div>

        <!-- Botons alineats horitzontalment -->
        <div class="flex space-x-4">
            <!-- Formulari d'eliminació d'usuari -->
            <form action="{{ route('users.manage.destroy', $user->id) }}" method="POST" data-qa="user-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" data-qa="confirm-delete" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Eliminar
                </button>
            </form>

            <!-- Enllaç per cancel·lar -->
            <a href="{{ route('users.manage.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Cancel·lar
            </a>
        </div>

    </div>
@endsection
