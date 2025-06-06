@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-md">
        <h2 class="text-2xl font-semibold mb-4 text-white text-center">Les teves notificacions</h2>

        @if (Auth::user()->notifications->isEmpty())
            <p class="text-gray-400 text-center py-8">No tens notificacions pendents.</p>
        @else
            <ul id="notification-list" class="space-y-3">
                @foreach (Auth::user()->notifications()->latest()->get() as $notification)
                    <li class="p-3 rounded-md {{ $notification->read_at ? 'bg-gray-800' : 'bg-gray-700' }} transition duration-200 text-gray-200"
                        data-id="{{ $notification->id }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex-1">
                                <strong class="text-base text-white">Nou vídeo:</strong>
                                <span class="block text-sm">{{ $notification->data['title'] ?? '—' }}</span>
                                <small class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <a href="{{ route('video.show', ['id' => $notification->data['video_id']]) }}"
                               class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition duration-200 text-center sm:text-left mt-2 sm:mt-0">
                                Veure vídeo
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <script>
        window.Echo.private('App.Models.User.' + {{ auth()->id() }})
            .notification((notification) => {
                const list = document.getElementById('notification-list');
                const item = document.createElement('li');

                item.className = 'p-3 bg-gray-700 rounded-md transition duration-200 text-gray-200';
                item.setAttribute('data-id', notification.id);

                item.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex-1">
                            <strong class="text-base text-white">Nou vídeo:</strong>
                            <span class="block text-sm">${notification.title || '—'}</span>
                            <small class="text-xs text-gray-400">ara mateix</small>
                        </div>
                        <a
                            href="/video/${notification.data.video_id}"
                            class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition duration-200 text-center sm:text-left mt-2 sm:mt-0"
                        >
                            Veure vídeo
                        </a>
                    </div>
                `;

                list.prepend(item);
            });
    </script>
@endsection
