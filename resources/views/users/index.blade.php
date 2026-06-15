<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('user-create'))"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-coinpel px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Adicionar usuário
            </button>
            <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 whitespace-nowrap">Filtrar</button>
            <form method="GET" action="{{ route('users.index') }}" class="ml-auto relative hidden sm:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar usuário"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="usersDrawer()" @user-create.window="openCreate()" class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Usuário</th>
                    <th class="px-4 py-3 font-medium">E-mail</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">
                            {{ $user->name }}
                            @if($user->blocked)
                                <span class="ml-2 rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-600">Bloqueado</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-800">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <button @click="menu = false; openEdit({{ $user->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar usuário
                                    </button>
                                    <form method="POST" action="{{ route('users.toggle-block', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <img src="{{ asset('icons/block.svg') }}" class="w-4 h-4" alt="">
                                            {{ $user->blocked ? 'Desbloquear usuário' : 'Bloquear usuário' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Excluir este usuário?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar usuário
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-12 text-center text-gray-500">Nenhum usuário cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Cards (mobile) --}}
        <div class="lg:hidden space-y-3">
            @forelse ($users as $user)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">
                                {{ $user->name }}
                                @if($user->blocked)
                                    <span class="ml-1 rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-600">Bloqueado</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
                            <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                            <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                <button @click="menu = false; openEdit({{ $user->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar usuário
                                </button>
                                <form method="POST" action="{{ route('users.toggle-block', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/block.svg') }}" class="w-4 h-4" alt="">
                                        {{ $user->blocked ? 'Desbloquear usuário' : 'Bloquear usuário' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Excluir este usuário?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar usuário
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhum usuário cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $users->links() }}</div>

        {{-- DRAWER --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Usuário</h2>
                    <span class="w-5"></span>
                </div>

                <form :action="actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="user_id" :value="user.id">

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nome completo:</label>
                        <input type="text" name="name" x-model="user.name" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">E-mail:</label>
                        <input type="email" name="email" x-model="user.email" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Senha provisória:</label>
                        <input type="password" name="password" x-model="user.password" autocomplete="new-password" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        <p x-show="mode === 'edit'" class="mt-1 text-xs text-gray-400">Deixe em branco para manter a senha atual.</p>
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2 pt-4">
                        <button type="submit" class="w-full rounded-lg bg-coinpel px-4 py-2.5 text-sm font-semibold text-white hover:bg-coinpel-dark" x-text="mode === 'edit' ? 'Salvar' : 'Finalizar cadastro'"></button>
                        <button type="button" @click="close()" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const STORE_URL = @json(route('users.store'));
        const usersData = {
            @foreach ($users as $user)
            "{{ $user->id }}": {
                id: {{ $user->id }},
                name: @json($user->name),
                email: @json($user->email),
                update_url: @json(route('users.update', $user)),
            },
            @endforeach
        };
        const oldInput = @json(old());
        const hasErrors = @json($errors->any());

        function emptyUser() { return { id: '', name: '', email: '', password: '' }; }

        function usersDrawer() {
            return {
                open: false,
                mode: 'create',
                user: emptyUser(),
                actionUrl: STORE_URL,
                openCreate() { this.user = emptyUser(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                openEdit(id) { this.user = { ...usersData[id], password: '' }; this.actionUrl = usersData[id].update_url; this.mode = 'edit'; this.open = true; },
                close() { this.open = false; },
                init() {
                    if (!hasErrors) return;
                    const id = oldInput.user_id;
                    const isEdit = id && usersData[id];
                    this.user = { ...(isEdit ? usersData[id] : emptyUser()), ...oldInput, password: '' };
                    this.actionUrl = isEdit ? usersData[id].update_url : STORE_URL;
                    this.mode = isEdit ? 'edit' : 'create';
                    this.open = true;
                },
            };
        }
    </script>
</x-app-layout>
