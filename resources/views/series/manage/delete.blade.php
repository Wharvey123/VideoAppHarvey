@extends('layouts.VideosAppLayout')

@section('content')
    @php
        $referer = request()->headers->get('referer');
        $isFromManage = str_contains($referer, 'seriesmanage');
        $cancelUrl = $isFromManage ? route('series.manage.index') : route('series.show', $serie->id);
    @endphp

    <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg text-white">
        <h1 class="text-2xl font-bold mb-4">Confirmar Eliminació</h1>
        <p class="mb-4">
            Estàs segur que vols eliminar la sèrie:
            <strong>{{ $serie->title }}</strong>?
        </p>

        <form action="{{ route('series.manage.destroy', $serie->id) }}" method="POST" class="flex gap-4">
            @csrf
            @method('DELETE')

            <input type="hidden" name="referer" value="{{ $referer }}">

            <button type="submit"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                Sí, eliminar
            </button>
            <a href="{{ $cancelUrl }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">
                Cancel·lar
            </a>
        </form>
    </div>
@endsection
