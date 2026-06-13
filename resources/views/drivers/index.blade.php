<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Motoristas</h2></x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('drivers.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Adicionar motorista</a>
                <form method="GET" action="{{ route('drivers.index') }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar motorista" class="border rounded px-3 py-2">
                </form>
            </div>

            <table class="w-full text-left">
                <thead>
                <tr class="border-b">
                    <th class="py-2 pr-4">Foto</th>
                    <th class="py-2 pr-4">Nome</th>
                    <th class="py-2 pr-4">E-mail</th>
                    <th class="py-2 pr-4">Telefone</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($drivers as $driver)
                    <tr class="border-b">
                        <td class="py-2 pr-4">
                            @if ($driver->profile_photo)
                                <img src="{{ asset('storage/'.$driver->profile_photo) }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200"></div>
                            @endif
                        </td>
                        <td class="py-2 pr-4">{{ $driver->name }}</td>
                        <td class="py-2 pr-4">{{ $driver->email }}</td>
                        <td class="py-2 pr-4">{{ $driver->phone }}</td>
                        <td class="py-2 text-right space-x-3">
                            <a href="{{ route('drivers.edit', $driver) }}" class="text-indigo-600">Editar</a>
                            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Remover este motorista?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-500">Nenhum motorista cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $drivers->links() }}</div>
        </div>
    </div>
</x-app-layout>
