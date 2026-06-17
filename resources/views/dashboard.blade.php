<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-gray-800">Estatísticas</h1>
    </x-slot>

    <div class="p-6 space-y-8">
        {{-- Viagens por status --}}
        <div>
            <h2 class="text-sm font-medium text-gray-500 mb-3">Viagens</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <p class="text-sm text-gray-500">Em andamento</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['trips_in_progress'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <p class="text-sm text-gray-500">Concluídas</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['trips_completed'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <p class="text-sm text-gray-500">Canceladas</p>
                    </div>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['trips_cancelled'] }}</p>
                </div>
            </div>
        </div>

        {{-- Totais dos cadastros --}}
        <div>
            <h2 class="text-sm font-medium text-gray-500 mb-3">Cadastros</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Motoristas</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['drivers'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Veículos</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['vehicles'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Clientes</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['customers'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Pacotes</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['packages'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Contratos</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $stats['contracts'] }}</p>
                </div>
            </div>
        </div>

        {{-- Financeiro --}}
        <div>
            <h2 class="text-sm font-medium text-gray-500 mb-3">Financeiro</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Valor total em contratos</p>
                    <p class="mt-2 text-3xl font-semibold text-coinpel">R$ {{ number_format($stats['contracts_total'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
