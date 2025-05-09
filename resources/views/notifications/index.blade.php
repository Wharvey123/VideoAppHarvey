@extends('layouts.VideosAppLayout')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl mb-4">Les teves notificacions</h2>

        {{-- Mostrem les notificacions emmagatzemades a la base de dades --}}
        @if (Auth::user()->notifications->isEmpty())
            <p class="text-gray-600">No tens notificacions pendents.</p>
        @else
            <ul id="notification-list" class="space-y-2">
                @foreach (Auth::user()->notifications()->latest()->get() as $notification)
                    <li
                        class="p-4 rounded-lg {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}"
                        data-id="{{ $notification->id }}"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <strong>Nou vídeo:</strong>
                                {{ $notification->data['title'] ?? '—' }}
                                <br>
                                <small class="text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if (is_null($notification->read_at))
                                <button
                                    class="mark-read px-2 py-1 bg-blue-500 text-white text-sm rounded"
                                    data-id="{{ $notification->id }}"
                                >
                                    Marca com a llegida
                                </button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <script>
        // Configura Echo per rebre notificacions en temps real
        window.Echo.private('App.Models.User.' + {{ auth()->id() }})
            .notification((notification) => {
                const list = document.getElementById('notification-list');
                const item = document.createElement('li');

                item.className = 'p-4 bg-blue-50 rounded-lg';
                item.setAttribute('data-id', notification.id);

                item.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <strong>Nou vídeo:</strong> ${notification.title}
                            <br>
                            <small class="text-gray-500">ara mateix</small>
                        </div>
                        <button
                            class="mark-read px-2 py-1 bg-blue-500 text-white text-sm rounded"
                            data-id="${notification.id}"
                        >
                            Marca com a llegida
                        </button>
                    </div>
                `;

                list.prepend(item);
            });

        // Funció per marcar notificació com a llegida via AJAX
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('mark-read')) {
                const id = e.target.dataset.id;
                fetch(`/notifications/${id}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).then(res => {
                    if (res.ok) {
                        const li = document.querySelector(`li[data-id="${id}"]`);
                        li.classList.remove('bg-blue-50');
                        li.classList.add('bg-white');
                        e.target.remove();
                    }
                });
            }
        });
    </script>
@endsection
