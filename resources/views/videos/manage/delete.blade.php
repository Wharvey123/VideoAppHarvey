@extends('layouts.VideosAppLayout')

@section('content')
    @php
        $redirect = request()->query('redirect');
        // If cancelling, go back to public show or manage index:
        $cancelUrl = $redirect
            ?? (auth()->user()->can('manage-videos')
                ? route('videos.manage.index')
                : route('video.show', $video->id));
    @endphp

    <div class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg shadow-lg text-white">
        <h1 class="text-2xl font-bold mb-4">Confirmar Eliminació</h1>
        <p class="mb-4">
            Estàs segur que vols eliminar el vídeo:
            <strong>{{ $video->title }}</strong>?
        </p>

        <form
            action="{{ route('videos.manage.destroy', $video->id) }}?redirect={{ urlencode($redirect) }}"
            method="POST"
            class="flex gap-4"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                data-qa="confirm-delete"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700"
            >
                Sí, eliminar
            </button>

            <a
                href="{{ $cancelUrl }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700"
            >
                Cancel·lar
            </a>
        </form>
    </div>
@endsection
