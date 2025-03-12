@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Llista d\'usuaris')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contenidor del títol i el formulari de cerca -->
        <div class="flex justify-between items-center my-6 sm:my-8">
            <!-- Títol principal -->
            <h1 class="text-3xl font-bold text-white">Llista d'usuaris</h1>

            <!-- Formulari de cerca -->
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center">
                <input type="text" name="search" placeholder="Cercar usuaris..." class="w-full px-4 py-2 rounded-l-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm flex items-center">
                    <!-- Icona de cerca -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Llista d'usuaris -->
        <ul class="space-y-4 sm:space-y-6">
            @foreach($users as $user)
                <li>
                    <a href="{{ route('users.show', $user->id) }}" class="block p-4 bg-gray-800 rounded-lg shadow hover:bg-gray-700 transition duration-300">
                        <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                        <p class="text-gray-400 mt-2">{{ $user->email }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
