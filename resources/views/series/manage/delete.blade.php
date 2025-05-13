@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg text-white">
            <h1 class="text-2xl font-bold mb-4">Confirmar Eliminació</h1>
            <p class="mb-4">
                Estàs segur que vols eliminar la sèrie <strong>{{ $serie->title }}</strong>?
            </p>
            <form action="{{ route('series.manage.destroy', $serie->id) }}?redirect={{ urlencode($redirect) }}" method="POST" class="inline-block mr-4">
                @csrf
                @method('DELETE')
                <button type="submit">
                    <x-button color="red" size="md">Sí, eliminar</x-button>
                </button>
            </form>
            <a href="{{ $redirect }}" class="inline-block">
                <x-button color="gray" size="md">Cancel·lar</x-button>
            </a>
        </div>
    </div>
@endsection
