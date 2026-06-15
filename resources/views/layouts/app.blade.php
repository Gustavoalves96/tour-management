<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'COINPEL') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="font-sans antialiased">
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">

    {{-- Fundo escuro quando a sidebar abre no mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

    {{-- Barra lateral --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-coinpel transform transition-transform duration-200 lg:translate-x-0">
        <div class="flex items-center justify-center h-24 border-b border-white/10">
            <img src="{{ asset('images/logo-white.png') }}" alt="COINPEL" class="h-16">
        </div>

        <nav class="mt-4 px-3 space-y-1">
            {{-- Clientes (placeholder) --}}
            <x-nav-side disabled>
                <img src="{{ asset('icons/system-uicons_users.svg') }}" class="w-6 h-6" alt="">
                <span>Clientes</span>
            </x-nav-side>

            {{-- Motoristas --}}
            <x-nav-side :href="route('drivers.index')" :active="request()->routeIs('drivers.*')">
                <img src="{{ asset('icons/system-uicons_drivers.svg') }}" class="w-6 h-6" alt="">
                <span>Motoristas</span>
            </x-nav-side>

            {{-- Estatísticas (placeholder) --}}
            <x-nav-side disabled>
                <img src="{{ asset('icons/system-uicons_graph-bar.svg') }}" class="w-6 h-6" alt="">
                <span>Estatísticas</span>
            </x-nav-side>

            {{-- Veículos --}}
            <x-nav-side :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                <img src="{{ asset('icons/carbon_bus.svg') }}" class="w-6 h-6" alt="">
                <span>Veículos</span>
            </x-nav-side>

            {{-- Viagens --}}
            <x-nav-side :href="route('trips.index')" :active="request()->routeIs('trips.*')">
                    <span class="relative inline-block w-6 h-6">
                        <img src="{{ asset('icons/carbon_bus.svg') }}" class="w-6 h-6" alt="">
                        <img src="{{ asset('icons/Group.svg') }}" class="w-3.5 h-3.5 absolute -bottom-1 -right-1" alt="">
                    </span>
                <span>Viagens</span>
            </x-nav-side>

            {{-- Contratos (placeholder) --}}
            <x-nav-side disabled>
                <img src="{{ asset('icons/teenyicons_contract-outline.svg') }}" class="w-6 h-6" alt="">
                <span>Contratos</span>
            </x-nav-side>

            {{-- Pacotes (placeholder) --}}
            <x-nav-side disabled>
                <img src="{{ asset('icons/ion_wallet-outline.svg') }}" class="w-6 h-6" alt="">
                <span>Pacotes</span>
            </x-nav-side>
        </nav>
    </aside>

    {{-- Área principal --}}
    <div class="lg:pl-64">
        {{-- Topo --}}
        <header class="bg-white shadow-sm">
            <div class="flex items-center gap-4 h-16 px-4 sm:px-6 lg:px-8">
                {{-- Hambúrguer (mobile) --}}
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Ações específicas da página --}}
                <div class="flex-1 min-w-0">
                    {{ $header ?? '' }}
                </div>

                {{-- Sino + usuário --}}
                <div class="flex items-center gap-4 shrink-0">
                    <button class="text-gray-500 hover:text-gray-700">
                        <img src="{{ asset('icons/clarity_notification-line.svg') }}" class="w-6 h-6" alt="Notificações">
                    </button>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-gray-700">
                                <span class="text-right leading-tight hidden sm:block">
                                    <span class="block text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <span class="block text-xs text-gray-500">Administrador</span>
                                </span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak @click.outside="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-40">
                            <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <img src="{{ asset('icons/usuarios.svg') }}" class="w-4 h-4" alt=""> Usuários
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <img src="{{ asset('icons/usuarios.svg') }}" class="w-4 h-4" alt=""> Meu perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <img src="{{ asset('icons/mdi-light_logout.svg') }}" class="w-4 h-4" alt=""> Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
