@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Confirmar Eliminació')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">Confirmar Eliminació</h1>

        <!-- Missatge de confirmació amb fons més visible -->
        <div class="bg-gray-800 p-6 rounded-lg mb-6">
            <p class="text-white">
                Estàs segur que vols eliminar el vídeo <strong class="text-red-500">{{ $video->title }}</strong>?
            </p>
        </div>

        <!-- Contenidor dels botons amb flexbox -->
        <div class="flex justify-center items-center space-x-4">
            <!-- Formulari d'eliminació de vídeo -->
            <form action="{{ route('videos.manage.destroy', $video->id) }}?redirect={{ urlencode($redirect) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Sí, eliminar
                </button>
            </form>

            <!-- Enllaç per cancel·lar -->
            <a href="{{ $redirect }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Cancel·lar
            </a>
        </div>
    </div>
@endsection
