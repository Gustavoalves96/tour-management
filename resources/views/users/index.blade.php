<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuários</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">+ Adicionar usuário</a>
                <form method="GET" action="{{ route('users.index') }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar usuário" class="border rounded px-3 py-2">
                </form>
            </div>

            <table class="w-full text-left">
                <thead>
                <tr class="border-b">
                    <th class="py-2">Usuário</th>
                    <th class="py-2">E-mail</th>
                    <th class="py-2">Status</th>
                    <th class="py-2 text-right">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="py-2">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2">
                            @if ($user->blocked)
                                <span class="text-red-600">Bloqueado</span>
                            @else
                                <span class="text-green-600">Ativo</span>
                            @endif
                        </td>
                        <td class="py-2 text-right space-x-3">
                            <a href="{{ route('users.edit', $user) }}" class="text-indigo-600">Editar</a>
                            <form action="{{ route('users.toggle-block', $user) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-yellow-600">{{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}</button>
                            </form>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Remover este usuário?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-500">Nenhum usuário encontrado.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
