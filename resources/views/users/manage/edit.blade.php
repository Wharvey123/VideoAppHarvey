@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Editar Usuari')

@section('content')
    @php
        // Recollim el redirect passat per query (o index com a fallback)
        $redirect = request()->query('redirect', route('users.manage.index'));
    @endphp

    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-white my-6">Editar Usuari</h1>

        <form action="{{ route('users.manage.update', $user->id) }}?redirect={{ urlencode($redirect) }}"
              method="POST" class="bg-gray-800 p-6 rounded-lg">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-white">Nom</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-white">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full p-2 bg-gray-700 text-white rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-white">
                    Nova Contrasenya (deixa en blanc per no canviar)
                </label>
                <input type="password" name="password" id="password"
                       class="w-full p-2 bg-gray-700 text-white rounded-lg">
            </div>

            <div class="mb-4">
                <label for="current_team_id" class="block text-gray-200 font-semibold">Equip</label>
                <select name="current_team_id" id="current_team_id"
                        class="w-full p-3 bg-gray-800 text-white border border-gray-600 rounded-lg">
                    <option value="">Sense equip</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}"
                            {{ old('current_team_id', $user->current_team_id) == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="roles" class="block text-gray-200 font-semibold">Rol</label>
                <select name="roles[]" id="roles"
                        class="w-full p-3 bg-gray-800 text-white border border-gray-600 rounded-lg">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-4 mt-6">
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Actualitzar
                </button>
                <a href="{{ $redirect }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Cancel·lar
                </a>
            </div>
        </form>
    </div>
@endsection
