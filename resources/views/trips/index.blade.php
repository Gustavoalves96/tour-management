<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button href="{{ route('trips.create') }}" label="Adicionar viagem" />

            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ ($status || $rule) ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="fixed inset-x-4 top-[4.5rem] sm:inset-x-auto sm:top-auto sm:absolute sm:right-0 sm:mt-2 sm:w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('trips.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todos</option>
                                @foreach(\App\Models\Trip::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Regra</label>
                            <select name="rule" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todas</option>
                                @foreach(\App\Models\Trip::RULES as $r)
                                    <option value="{{ $r }}" {{ $rule === $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('trips.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Busca --}}
            <form method="GET" action="{{ route('trips.index') }}" class="ml-auto relative hidden sm:block">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="rule" value="{{ $rule }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar viagem"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
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

        {{-- Cards (mobile) --}}
        <div class="lg:hidden space-y-3">
            @forelse ($trips as $trip)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">{{ $trip->name }}</p>
                            <p class="text-sm text-gray-500">{{ \App\Models\Trip::STATUSES[$trip->status] ?? $trip->status }}</p>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
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
                    </div>
                    <p class="mt-3 flex items-center gap-2 text-sm text-gray-700">
                        {{ \Illuminate\Support\Str::limit($trip->origin, 14, ' (...)') }}
                        <img src="{{ asset('icons/seta_direita.svg') }}" class="h-3" alt="›">
                        {{ \Illuminate\Support\Str::limit($trip->destination, 14, ' (...)') }}
                    </p>
                    <div class="mt-1 grid grid-cols-2 gap-y-1 text-sm text-gray-700">
                        <div><span class="text-gray-400">Data:</span> {{ \Carbon\Carbon::parse($trip->date)->format('d/m/Y') }}</div>
                        <div><span class="text-gray-400">Horário:</span> @if($trip->departure_time){{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}@endif</div>
                        <div><span class="text-gray-400">Veículo:</span> {{ $trip->vehicle?->prefix }} - {{ $trip->vehicle?->model }}</div>
                        <div><span class="text-gray-400">Regra:</span> {{ $trip->rule }}</div>
                        <div class="col-span-2"><span class="text-gray-400">Motorista:</span> {{ $trip->driver?->name }}</div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhuma viagem cadastrada.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $trips->links() }}</div>
    </div>
</x-app-layout>
