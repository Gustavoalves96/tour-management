<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('vehicles.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-coinpel px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Adicionar veículo
            </a>
            <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 whitespace-nowrap">Filtrar</button>
            <form method="GET" action="{{ route('vehicles.index') }}" class="ml-auto relative hidden sm:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar veículo"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Prefixo</th>
                    <th class="px-4 py-3 font-medium">Placa</th>
                    <th class="px-4 py-3 font-medium">Modelo</th>
                    <th class="px-4 py-3 font-medium">Chassi</th>
                    <th class="px-4 py-3 font-medium">Tipo de veículo</th>
                    <th class="px-4 py-3 font-medium">Capacidade</th>
                    <th class="px-4 py-3 font-medium">Ano</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->prefix }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->plate }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->model }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->chassis }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->type }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->capacity }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->year }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar veículo
                                    </a>
                                    <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Excluir este veículo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar veículo
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">Nenhum veículo cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $vehicles->links() }}</div>
    </div>
</x-app-layout>
