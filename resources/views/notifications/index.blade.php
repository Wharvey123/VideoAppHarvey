@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl mb-4">Les teves notificacions</h2>
        <ul id="notification-list" class="space-y-2">
            <!-- Aquí arribaran les notificacions via Echo -->
        </ul>
    </div>

    <script>
        window.Echo.private('App.Models.User.' + {{ auth()->id() }})
            .notification((notification) => {
                const list = document.getElementById('notification-list');
                const item = document.createElement('li');
                item.className = 'p-2 bg-gray-200 rounded';
                item.innerHTML = `Nou vídeo: <strong>${notification.title}</strong>`;
                list.prepend(item);
            });
    </script>
@endsection
