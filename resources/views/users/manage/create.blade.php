@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Crear Usuari')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">Crear Usuari</h1>

        <!-- Missatge d'èxit -->
        @if(session('success'))
            <div id="success-message" class="mb-6 p-4 bg-green-500 text-white rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulari de creació -->
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
            <!-- Botons alineats horitzontalment -->
            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Crear Usuari</button>
                <a href="{{ route('users.manage.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Tornar</a>
            </div>
        </form>
    </div>

    <!-- Script per eliminar el missatge d'èxit després de 3 segons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000); // 3000 mil·lisegons = 3 segons
            }
        });
    </script>
@endsection
