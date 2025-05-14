@extends('layouts.VideosAppLayout')

@section('title', "VideosApp - Detalls de l'usuari")

@section('content')
    @php
        $viewer   = auth()->user();
        $showUser = $user; // Usuari passat pel controlador

        $isManager = $viewer && $viewer->can('manage-users');
        $owns      = $viewer && $viewer->id === $showUser->id;

        $redirect = urlencode(route('users.show', $showUser->id));
    @endphp

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center">
                <div class="bg-blue-500/20 p-3 rounded-full mr-4">
                    <i class="fas fa-user-circle text-blue-400 text-xl"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Detalls de l'usuari</h1>
            </div>
            <a href="{{ route('users.index') }}"
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Tornar a la llista
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700">
            <div class="bg-gray-900 p-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="w-24 h-24 rounded-full bg-blue-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-user text-4xl text-white"></i>
                </div>
                <div class="text-center sm:text-left">
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $showUser->name }}</h2>
                    <p class="text-gray-300">{{ $showUser->email }}</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <div class="border-b border-gray-700 pb-4">
                        <p class="text-sm font-medium text-gray-400 mb-1">ID d'usuari</p>
                        <p class="text-white font-mono bg-gray-700/50 px-3 py-1.5 rounded inline-block">{{ $showUser->id }}</p>
                    </div>
                    <div class="border-b border-gray-700 pb-4">
                        <p class="text-sm font-medium text-gray-400 mb-2">Rols assignats</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($showUser->roles as $role)
                                <span class="px-3 py-1 rounded-full text-sm bg-indigo-500/20 text-indigo-300">
                                    <i class="fas fa-user-tag mr-1"></i>{{ $role->name }}
                                </span>
                            @empty
                                <span class="text-gray-400 text-sm">Sense rols assignats</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="border-b border-gray-700 pb-4">
                        <p class="text-sm font-medium text-gray-400 mb-1">Data de registre</p>
                        <p class="text-white flex items-center">
                            <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                            {{ $showUser->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="border-b border-gray-700 pb-4">
                        <p class="text-sm font-medium text-gray-400 mb-1">Última actualització</p>
                        <p class="text-white flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>
                            {{ $showUser->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            @can('edit', $showUser)
                <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
                    <div class="flex flex-wrap justify-end gap-3">
                        @if($owns || $isManager)
                            <a href="{{ route('users.manage.edit', $showUser->id) }}?redirect={{ $redirect }}"
                               class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 px-4 py-2 rounded-lg transition flex items-center">
                                <i class="fas fa-edit mr-2"></i>Editar usuari
                            </a>
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
