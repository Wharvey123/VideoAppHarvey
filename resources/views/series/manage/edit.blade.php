@extends('layouts.VideosAppLayout')

@section('content')
    @php
        $redirect  = request('redirect');
        $cancelUrl = $redirect
            ?? (auth()->user()->can('manage-series')
                ? route('series.manage.index')
                : route('series.show', $serie->id));
    @endphp

    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-white">Editar Sèrie</h1>
        <form action="{{ route('series.manage.update', $serie->id) }}?redirect={{ urlencode($redirect) }}"
              method="POST">
            @csrf
            @method('PUT')

            @if($serie->image)
                <div class="mb-4 text-center">
                    <img src="{{ $serie->image }}" alt="Portada actual"
                         class="w-32 h-32 object-cover rounded mx-auto mb-2">
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Títol</label>
                <input name="title" value="{{ $serie->title }}"
                       class="w-full p-2 bg-gray-700 text-white rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Descripció</label>
                <textarea name="description" rows="4"
                          class="w-full p-2 bg-gray-700 text-white rounded" required>{{ $serie->description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-white mb-1">Nova Portada (URL)</label>
                <input name="image" type="url" value="{{ $serie->image }}"
                       class="w-full p-2 bg-gray-700 text-white rounded">
            </div>

            <div class="flex justify-between">
                <a href="{{ $cancelUrl }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Cancel·lar
                </a>
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Actualitzar Sèrie
                </button>
            </div>
        </form>
    </div>
@endsection
