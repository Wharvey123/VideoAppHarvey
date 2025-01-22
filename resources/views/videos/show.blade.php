<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 font-sans leading-relaxed tracking-normal">

<div class="container mx-auto px-4 py-8">
    <!-- Encapçalament -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-white">{{ $video->title }}</h1>
        <p class="text-sm text-gray-400 mt-2">
            Publicat: {{ $video->formatted_published_at }}
            <span class="text-gray-500">|</span>
            {{ $video->formatted_for_humans_published_at }}
        </p>
    </div>

    <!-- Contingut del vídeo -->
    <div class="mt-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <iframe
                class="w-full"
                style="height: calc(100vw * 9 / 16); max-height: 500px;"
                src="{{ $video->url }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>

    <!-- Descripció -->
    <div class="mt-8 bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-white">Descripció</h2>
        <p class="text-gray-300 mt-4">{{ $video->description }}</p>
    </div>

    <!-- Navegació entre vídeos -->
    <div class="mt-8 flex justify-between items-center text-sm">
        @if($video->previous)
            <a href="{{ url('/video/' . $video->previous) }}"
               class="text-blue-400 hover:text-blue-600 font-medium">
                &larr; Vídeo anterior
            </a>
        @else
            <span class="text-gray-500">No hi ha vídeo anterior</span>
        @endif

        @if($video->next)
            <a href="{{ url('/video/' . $video->next) }}"
               class="text-blue-400 hover:text-blue-600 font-medium">
                Vídeo següent &rarr;
            </a>
        @else
            <span class="text-gray-500">No hi ha vídeo següent</span>
        @endif
    </div>
</div>

</body>
</html>
