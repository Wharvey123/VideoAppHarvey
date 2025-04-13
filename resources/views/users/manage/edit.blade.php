@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Editar Usuari')

@section('content')
    <div class="container mx-auto px-4">
        <!-- Títol principal -->
        <h1 class="text-3xl font-bold text-white my-6">Editar Usuari</h1>

        <!-- Missatge d'èxit -->
        @if(session('success'))
            <div id="success-message" class="mb-6 p-4 bg-green-500 text-white rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulari d'edició -->
        <form action="{{ route('users.manage.update', $user->id) }}" method="POST" class="bg-gray-800 p-6 rounded-lg">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-white">Nom</label>
                <input type="text" name="name" id="name" class="w-full p-2 bg-gray-700 text-white rounded-lg" value="{{ $user->name }}" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 bg-gray-700 text-white rounded-lg" value="{{ $user->email }}" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white">Nova Contrasenya (deixa en blanc per no canviar)</label>
                <input type="password" name="password" id="password" class="w-full p-2 bg-gray-700 text-white rounded-lg">
            </div>
            <div class="mb-4">
                <label for="current_team_id" class="block text-gray-200 font-semibold">Equip</label>
                <div class="relative">
                    <select name="current_team_id" id="current_team_id" class="w-full p-3 bg-gray-800 text-white border border-gray-600 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sense equip</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $user->current_team_id == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ optional($team->user)->name ?? 'Sense propietari' }})
                            </option>
                        @endforeach
                    </select>
                    <!-- Fletxa del desplegable -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M5.516 7.548l4.484 4.482 4.482-4.482"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Desplegable de Rols (canviat a roles[] per consistència amb la creació) -->
            <div class="mb-4">
                <label for="roles" class="block text-gray-200 font-semibold">Rol</label>
                <div class="relative">
                    <select name="roles[]" id="roles" class="w-full p-3 bg-gray-800 text-white border border-gray-600 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Fletxa del desplegable -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M5.516 7.548l4.484 4.482 4.482-4.482"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Botons alineats horitzontalment -->
            <div class="flex space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Actualitzar Usuari
                </button>
                <a href="{{ route('users.manage.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Tornar
                </a>
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
                }, 3000);
            }
        });
    </script>
@endsection
