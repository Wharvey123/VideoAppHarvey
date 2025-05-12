<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-[#100c0c]">

<!-- Navbar -->
<nav class="bg-gray-800 p-6 shadow-lg" x-data="{ menuOpen: false }">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Hamburger Menu Icon (Mobile) -->
        <div class="lg:hidden">
            <button @click="menuOpen = true" type="button" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Enllaços de navegació per a desktop -->
        <div class="hidden lg:flex justify-between w-full max-w-5xl mx-auto">
            <!-- Vídeos -->
            <a href="{{ route('videos.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Vídeos</a>

            <!-- Manage Vídeos (només amb permís) -->
            @can('manage-videos')
                <a href="{{ route('videos.manage.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Manage Vídeos</a>
            @endcan

            <!-- Sèries -->
            <a href="{{ route('series.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Sèries</a>

            <!-- Manage Sèries (només amb permís) -->
            @can('manage-series')
                <a href="{{ route('series.manage.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Manage Sèries</a>
            @endcan

            <!-- Users -->
            <a href="{{ route('users.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Users</a>

            <!-- Manage Users (només amb permís) -->
            @can('manage-users')
                <a href="{{ route('users.manage.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Manage Users</a>
            @endcan

            <!-- Notificacions (només per a superadmins) -->
            @if(auth()->user() && auth()->user()->hasRole('superadmin'))
                <a href="{{ route('notifications.index') }}" class="text-white text-lg font-semibold flex-grow text-center">Notificacions</a>
            @endif
        </div>

        <!-- Autenticació -->
        <div class="ml-auto relative" x-data="{ open: false }">
            @auth
                <button @click="open = !open" class="flex items-center text-white focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Perfil" class="w-8 h-8 rounded-full">
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-200">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="text-white bg-blue-500 px-4 py-2 rounded-md hover:bg-blue-600">Login</a>
            @endauth
        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div x-show="menuOpen" @click.away="menuOpen = false"
         class="lg:hidden fixed inset-0 z-20 bg-black bg-opacity-50" aria-hidden="true">
        <div class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 p-6">
            <!-- Close Button -->
            <div class="flex justify-end">
                <button @click="menuOpen = false" type="button" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Links -->
            <div class="mt-6 space-y-4">
                <a href="{{ route('videos.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Vídeos</a>

                @can('manage-videos')
                    <a href="{{ route('videos.manage.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Manage Vídeos</a>
                @endcan

                <a href="{{ route('series.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Sèries</a>

                @can('manage-series')
                    <a href="{{ route('series.manage.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Manage Sèries</a>
                @endcan

                <a href="{{ route('users.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Users</a>

                @can('manage-users')
                    <a href="{{ route('users.manage.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Manage Users</a>
                @endcan

                @if(auth()->user() && auth()->user()->hasRole('superadmin'))
                    <a href="{{ route('notifications.index') }}" class="block text-white text-lg font-semibold hover:text-gray-300">Notificacions</a>
                @endif
            </div>
            <!-- Authentication Links (Mobile) -->
            <div class="mt-6">
                @auth
                    <a href="{{ route('profile.show') }}"
                       class="block text-white text-lg font-semibold hover:text-gray-300">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left text-white text-lg font-semibold hover:text-gray-300">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block text-white text-lg font-semibold hover:text-gray-300">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Contingut principal -->
<div class="flex-grow">
    <div class="container mx-auto py-6">
        @yield('content')
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 p-6 mt-auto">
    <div class="container mx-auto text-center text-white">
        © {{ date('Y') }} Harvey John Glover. Tots els drets reservats.
    </div>
</footer>

</body>
</html>
