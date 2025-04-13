@extends('layouts.VideosAppLayout')

@section('content')
    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-white">Confirmar Eliminació</h1>
        <p class="text-white mb-6">Estàs segur que vols eliminar la sèrie <strong class="text-red-400">{{ $serie->title }}</strong>? Això també afectarà els vídeos associats.</p>

        <form action="{{ route('series.manage.destroy', $serie->id) }}" method="POST" class="flex justify-between">
            @csrf
            @method('DELETE')
            <a href="{{ route('series.manage.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Cancel·lar</a>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Sí, Eliminar</button>
        </form>
    </div>
@endsection
