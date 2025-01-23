<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vídeo App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 font-sans leading-relaxed tracking-normal">

<!-- Contingut principal -->
<div class="container mx-auto px-4 py-8">
    @yield('content')
</div>

</body>
</html>
