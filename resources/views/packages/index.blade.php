<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button event="package-create" label="Adicionar pacote" />

            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ $destination ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('packages.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Destino</label>
                            <select name="destination" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todos</option>
                                @foreach ($destinations as $d)
                                    <option value="{{ $d }}" {{ $destination === $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('packages.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Busca --}}
            <form method="GET" action="{{ route('packages.index') }}" class="ml-auto relative hidden sm:block">
                <input type="hidden" name="destination" value="{{ $destination }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar pacote"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="packageDrawer()" @package-create.window="openCreate()" class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Nome</th>
                    <th class="px-4 py-3 font-medium">Rota</th>
                    <th class="px-4 py-3 font-medium">Duração</th>
                    <th class="px-4 py-3 font-medium">Capacidade</th>
                    <th class="px-4 py-3 font-medium">Preço</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($packages as $package)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $package->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $package->origin }} → {{ $package->destination }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $package->duration_days }} dias</td>
                        <td class="px-4 py-3 text-gray-800">{{ $package->max_people }} pessoas</td>
                        <td class="px-4 py-3 text-gray-800">R$ {{ number_format($package->price, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <button @click="menu = false; openEdit({{ $package->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar pacote
                                    </button>
                                    <form method="POST" action="{{ route('packages.destroy', $package) }}" onsubmit="return confirm('Excluir este pacote?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar pacote
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Nenhum pacote cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-3">
            @forelse ($packages as $package)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">{{ $package->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $package->origin }} → {{ $package->destination }}</p>
                            <p class="text-sm text-gray-500">{{ $package->duration_days }} dias · {{ $package->max_people }} pessoas</p>
                            <p class="text-sm text-gray-500">R$ {{ number_format($package->price, 2, ',', '.') }}</p>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
                            <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                            <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                <button @click="menu = false; openEdit({{ $package->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar pacote
                                </button>
                                <form method="POST" action="{{ route('packages.destroy', $package) }}" onsubmit="return confirm('Excluir este pacote?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar pacote
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhum pacote cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $packages->links() }}</div>

        {{-- DRAWER --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Pacote</h2>
                    <span class="w-5"></span>
                </div>

                <form :action="actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="package_id" :value="package.id">

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nome:</label>
                        <input type="text" name="name" x-model="package.name" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Origem:</label>
                        <input type="text" name="origin" x-model="package.origin" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('origin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Destino:</label>
                        <input type="text" name="destination" x-model="package.destination" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('destination') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Preço:</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">R$</span>
                            <input type="text" name="price" x-model="package.price" x-mask:dynamic="$money($input, ',', '.')" inputmode="decimal"
                                   class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-coinpel focus:ring-coinpel">
                        </div>
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Duração (dias):</label>
                        <input type="number" min="1" name="duration_days" x-model="package.duration_days" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('duration_days') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Capacidade máxima (pessoas):</label>
                        <input type="number" min="1" name="max_people" x-model="package.max_people" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('max_people') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Descrição:</label>
                        <textarea name="description" x-model="package.description" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel"></textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
        const STORE_URL = @json(route('packages.store'));
        const packagesData = {
            @foreach ($packages as $package)
            "{{ $package->id }}": {
                id: {{ $package->id }},
                name: @json($package->name),
                origin: @json($package->origin),
                destination: @json($package->destination),
                price: "{{ number_format($package->price, 2, ',', '.') }}",
                duration_days: @json($package->duration_days),
                max_people: @json($package->max_people),
                description: @json($package->description),
                update_url: @json(route('packages.update', $package)),
            },
            @endforeach
        };
        const oldInput = @json(old());
        const hasErrors = @json($errors->any());

        function emptyPackage() { return { id: '', name: '', origin: '', destination: '', price: '', duration_days: '', max_people: '', description: '' }; }

        function packageDrawer() {
            return {
                open: false,
                mode: 'create',
                package: emptyPackage(),
                actionUrl: STORE_URL,
                openCreate() { this.package = emptyPackage(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                openEdit(id) { this.package = { ...packagesData[id] }; this.actionUrl = packagesData[id].update_url; this.mode = 'edit'; this.open = true; },
                close() { this.open = false; },
                init() {
                    if (!hasErrors) return;
                    const id = oldInput.package_id;
                    const isEdit = id && packagesData[id];
                    this.package = { ...(isEdit ? packagesData[id] : emptyPackage()), ...oldInput };
                    this.actionUrl = isEdit ? packagesData[id].update_url : STORE_URL;
                    this.mode = isEdit ? 'edit' : 'create';
                    this.open = true;
                },
            };
        }
    </script>
</x-app-layout>
