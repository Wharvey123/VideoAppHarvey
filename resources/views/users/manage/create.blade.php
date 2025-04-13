@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Crear Usuari')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white my-6">Crear Usuari</h1>

        @if(session('success'))
            <div id="success-message" class="mb-6 p-4 bg-green-500 text-white rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.manage.store') }}" method="POST" class="bg-gray-800 p-6 rounded-lg">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-white">Nom</label>
                <input type="text" name="name" id="name" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white">Contrasenya</label>
                <input type="password" name="password" id="password" class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>

            <!-- Desplegable de Rols -->
            <div class="mb-4">
                <label for="roles" class="block text-gray-200 font-semibold">Rol</label>
                <div class="relative">
                    <select name="roles[]" id="roles" class="w-full p-3 bg-gray-800 text-white border border-gray-600 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ strtolower($role->name) === 'regular' ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M5.516 7.548l4.484 4.482 4.482-4.482"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Crear Usuari
                </button>
                <a href="{{ route('users.manage.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Tornar
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000);
            }
        });
    </script>
@endsection
