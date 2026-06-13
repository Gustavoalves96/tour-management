<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Viagens</h2></x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        @php
            $statusColors = [
                'in_progress' => 'bg-yellow-100 text-yellow-800',
                'completed' => 'bg-green-100 text-green-800',
                'cancelled' => 'bg-red-100 text-red-800',
            ];
        @endphp

        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('trips.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Adicionar viagem</a>
                <form method="GET" action="{{ route('trips.index') }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar viagem" class="border rounded px-3 py-2">
                </form>
            </div>

            <table class="w-full text-left whitespace-nowrap">
                <thead>
                <tr class="border-b">
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Nome</th>
                    <th class="py-2 pr-4">Data</th>
                    <th class="py-2 pr-4">Horário</th>
                    <th class="py-2 pr-4">Rota</th>
                    <th class="py-2 pr-4">Veículo</th>
                    <th class="py-2 pr-4">Regra</th>
                    <th class="py-2 pr-4">Motorista</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($trips as $trip)
                    <tr class="border-b">
                        <td class="py-2 pr-4">
                                <span class="px-2 py-1 rounded text-xs {{ $statusColors[$trip->status] ?? '' }}">
                                    {{ \App\Models\Trip::STATUSES[$trip->status] ?? $trip->status }}
                                </span>
                        </td>
                        <td class="py-2 pr-4">{{ $trip->name }}</td>
                        <td class="py-2 pr-4">{{ $trip->date->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ substr($trip->departure_time, 0, 5) }}</td>
                        <td class="py-2 pr-4">{{ $trip->origin }} → {{ $trip->destination }}</td>
                        <td class="py-2 pr-4">{{ $trip->vehicle?->prefix }} - {{ $trip->vehicle?->model }}</td>
                        <td class="py-2 pr-4">{{ $trip->rule }}</td>
                        <td class="py-2 pr-4">{{ $trip->driver?->name }}</td>
                        <td class="py-2 text-right space-x-3">
                            <a href="{{ route('trips.edit', $trip) }}" class="text-indigo-600">Editar</a>
                            <form action="{{ route('trips.destroy', $trip) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta viagem?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-4 text-center text-gray-500">Nenhuma viagem cadastrada.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $trips->links() }}</div>
        </div>
    </div>
</x-app-layout>
