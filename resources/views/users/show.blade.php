@extends('layouts.VideosAppLayout')

@section('title', 'VideosApp - Detalls de l\'usuari')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Capçalera -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-user-circle mr-2"></i>Detalls de l'usuari
            </h1>
            <a href="{{ route('users.index') }}" class="btn bg-blue-500 hover:bg-blue-600 rounded-full text-white px-4 py-2 inline-flex items-center justify-center whitespace-nowrap">
                <i class="fas fa-arrow-left mx-1"></i> <!-- Canviat mr-2 per mx-1 -->
                Tornar
            </a>
        </div>

        <!-- Targeta principal -->
        <div class="bg-gray-900 rounded-xl shadow-xl overflow-hidden">
            <!-- Secció superior amb avatar -->
            <div class="bg-gray-800 p-6 flex items-center">
                <div class="avatar-wrapper mr-4">
                    <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center">
                        <i class="fas fa-user text-3xl text-white"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-300">{{ $user->email }}</p>
                </div>
            </div>

            <!-- Cos de la targeta -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna esquerra -->
                <div class="space-y-4">
                    <div class="info-group">
                        <label class="text-gray-300 text-sm">ID d'usuari</label>
                        <p class="text-white font-mono">{{ $user->id }}</p>
                    </div>

                    <div class="info-group">
                        <label class="text-gray-300 text-sm">Equip actual</label>
                        <div class="badge bg-blue-500/20 text-white">
                            <i class="fas fa-users mr-2"></i>
                            {{ $user->currentTeam?->name ?? 'Sense equip' }}
                        </div>
                    </div>

                    <div class="info-group">
                        <label class="text-gray-300 text-sm">Rol principal</label>
                        <div class="badge bg-purple-500/20 text-white">
                            <i class="fas fa-shield-alt mr-2"></i>
                            {{ $user->roles->first()?->name ?? 'Sense rol' }}
                        </div>
                    </div>
                </div>

                <!-- Columna dreta -->
                <div class="space-y-4">
                    <div class="info-group">
                        <label class="text-gray-300 text-sm">Data de registre</label>
                        <p class="text-white">
                            <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="info-group">
                        <label class="text-gray-300 text-sm">Última actualització</label>
                        <p class="text-white">
                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    @auth
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->id === $user->id)
                            <div class="info-group">
                                <label class="text-gray-300 text-sm">Accions</label>
                                <a href="{{ route('profile.show') }}" class="text-blue-400 hover:text-blue-300 flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Canviar contrasenya
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Peu de la targeta només si és SuperAdmin -->
            @if($user->isSuperAdmin())
                <div class="bg-gray-800 px-6 py-3">
                    <p class="text-sm text-gray-300">
                        <i class="fas fa-info-circle mr-2"></i>
                        Usuari Administrador
                    </p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .info-group {
            @apply border-b border-gray-700 pb-4;
        }
        .info-group:last-child {
            @apply border-b-0 pb-0;
        }
        .badge {
            @apply px-3 py-1 rounded-full text-sm inline-flex items-center;
        }
        .btn {
            @apply px-4 py-2 rounded-full text-white transition-all duration-200 flex items-center;
        }
    </style>
@endsection
