@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Gestió d\'usuaris')

@section('content')
    <h1 class="text-5xl text-white mb-6 text-center">Gestió d'usuaris</h1>
    <div class="flex justify-center mb-6">
        <a href="{{ route('users.manage.create') }}" class="bg-gray-800 text-white px-5 py-2 rounded hover:bg-gray-700 transition-colors">
            Afegir Usuari
        </a>
    </div>
    <div class="flex justify-center">
        <div class="w-full lg:w-4/5 px-4 py-5">
            <div class="bg-white rounded-lg shadow-xl overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-white font-semibold">ID</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">Nom</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">Email</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">Equip</th>
                        <th class="px-6 py-4 text-left text-white font-semibold">Rols</th>
                        <th class="px-6 py-4 text-center text-white font-semibold">Accions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="px-6 py-4 text-gray-800">{{ $user->id }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-800">
                                {{ $user->currentTeam ? $user->currentTeam->name : 'Sense equip' }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">
                                @foreach($user->roles as $role)
                                    @php
                                        $colorClass = 'bg-gray-500';
                                        if(strtolower($role->name) == 'regular'){
                                            $colorClass = 'bg-blue-500';
                                        } elseif(strtolower($role->name) == 'videomanager'){
                                            $colorClass = 'bg-green-500';
                                        } elseif(strtolower($role->name) == 'superadmin'){
                                            $colorClass = 'bg-red-500';
                                        }
                                    @endphp
                                    <span class="inline-block {{ $colorClass }} text-white text-xs font-semibold mr-2 px-3 py-1 rounded-full">
                                            {{ $role->name }}
                                        </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('users.manage.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Editar
                                    </a>
                                    <a href="{{ route('users.manage.delete', $user->id) }}" class="text-red-600 hover:text-red-800 font-medium">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div id="success-message" class="mb-6 p-4 bg-green-500 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 2500);
            }
        });
    </script>
@endsection
