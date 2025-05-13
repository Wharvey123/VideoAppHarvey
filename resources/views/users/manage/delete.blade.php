@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Confirmar Eliminació')

@section('content')
    @php
        // Recollim el redirect passat per query (o index com a fallback)
        $redirect = request()->query('redirect', route('users.manage.index'));
    @endphp

    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white my-6">Confirmar Eliminació</h1>
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg text-white mb-6">
            Estàs segur que vols eliminar l'usuari <strong class="text-red-500">{{ $user->name }}</strong>?
        </div>

        <form action="{{ route('users.manage.destroy', $user->id) }}?redirect={{ urlencode($redirect) }}"
              method="POST" class="inline-block mr-4">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                Sí, eliminar
            </button>
        </form>

        <a href="{{ $redirect }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 inline-block">
            Cancel·lar
        </a>
    </div>
@endsection
