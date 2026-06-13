<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Veículos</h2></x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('vehicles.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Adicionar veículo</a>
                <form method="GET" action="{{ route('vehicles.index') }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar veículo" class="border rounded px-3 py-2">
                </form>
            </div>

            <table class="w-full text-left whitespace-nowrap">
                <thead>
                <tr class="border-b">
                    <th class="py-2 pr-4">Prefixo</th>
                    <th class="py-2 pr-4">Placa</th>
                    <th class="py-2 pr-4">Modelo</th>
                    <th class="py-2 pr-4">Chassi</th>
                    <th class="py-2 pr-4">Tipo</th>
                    <th class="py-2 pr-4">Capacidade</th>
                    <th class="py-2 pr-4">Ano</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr class="border-b">
                        <td class="py-2 pr-4">{{ $vehicle->prefix }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->plate }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->model }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->chassis }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->type }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->capacity }}</td>
                        <td class="py-2 pr-4">{{ $vehicle->year }}</td>
                        <td class="py-2 text-right space-x-3">
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-indigo-600">Editar</a>
                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline" onsubmit="return confirm('Remover este veículo?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 text-center text-gray-500">Nenhum veículo cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $vehicles->links() }}</div>
        </div>
    </div>
</x-app-layout>
