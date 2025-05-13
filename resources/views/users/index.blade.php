@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Llista d\'usuaris')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Capçalera amb disseny millorat -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 my-6 sm:my-8">
            <h1 class="text-3xl font-bold text-white">Llista d'usuaris</h1>

            <!-- Cercador simplificat -->
            <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-96">
                <div class="relative">
                    <!-- Camp de cerca amb icona integrada -->
                    <input type="text"
                           name="search"
                           placeholder="Cercar usuaris..."
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 hover:bg-gray-600">

                    <!-- Icona de cerca -->
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Botó per esborrar cerca (visible quan hi ha text) -->
                    @if(request('search'))
                        <button type="button"
                                onclick="document.querySelector('input[name=\'search\']').value='';document.forms[0].submit()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-white">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Llista d'usuaris amb hover com abans -->
        <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
            <ul class="divide-y divide-gray-700">
                @foreach($users as $user)
                    <li>
                        <a href="{{ route('users.show', $user->id) }}" class="block p-4 hover:bg-gray-700 transition duration-300">
                            <h3 class="text-lg font-semibold text-white">{{ $user->name }}</h3>
                            <p class="text-gray-400">{{ $user->email }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
