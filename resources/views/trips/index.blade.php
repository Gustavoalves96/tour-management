<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('trips.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-coinpel px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Adicionar viagem
            </a>
            <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 whitespace-nowrap">Filtrar</button>
            <form method="GET" action="{{ route('trips.index') }}" class="ml-auto relative hidden sm:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar viagem"
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
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Nome</th>
                    <th class="px-4 py-3 font-medium">Data</th>
                    <th class="px-4 py-3 font-medium">Horário</th>
                    <th class="px-4 py-3 font-medium">Rota</th>
                    <th class="px-4 py-3 font-medium">Veículo</th>
                    <th class="px-4 py-3 font-medium">Regra</th>
                    <th class="px-4 py-3 font-medium">Motorista</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($trips as $trip)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ \App\Models\Trip::STATUSES[$trip->status] ?? $trip->status }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $trip->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ \Carbon\Carbon::parse($trip->date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-800">@if($trip->departure_time){{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}@endif</td>
                        <td class="px-4 py-3 text-gray-800">
                                <span class="inline-flex items-center gap-2">
                                    {{ \Illuminate\Support\Str::limit($trip->origin, 10, ' (...)') }}
                                    <img src="{{ asset('icons/seta_direita.svg') }}" class="h-3" alt="›">
                                    {{ \Illuminate\Support\Str::limit($trip->destination, 10, ' (...)') }}
                                </span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">{{ $trip->vehicle?->prefix }} - {{ $trip->vehicle?->model }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $trip->rule }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $trip->driver?->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <a href="{{ route('trips.edit', $trip) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar viagem
                                    </a>
                                    <form method="POST" action="{{ route('trips.destroy', $trip) }}" onsubmit="return confirm('Excluir esta viagem?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar viagem
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-12 text-center text-gray-500">Nenhuma viagem cadastrada.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $trips->links() }}</div>
    </div>
</x-app-layout>
