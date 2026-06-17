<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button event="customer-create" label="Adicionar Cliente" />

            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ $city ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="fixed inset-x-4 top-[4.5rem] sm:inset-x-auto sm:top-auto sm:absolute sm:right-0 sm:mt-2 sm:w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('customers.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Cidade</label>
                            <select name="city" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todas</option>
                                @foreach ($cities as $c)
                                    <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('customers.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Busca --}}
            <form method="GET" action="{{ route('customers.index') }}" class="ml-auto relative hidden sm:block">
                <input type="hidden" name="city" value="{{ $city }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar cliente"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="customerDrawer()" @customer-create.window="openCreate()" class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Nome</th>
                    <th class="px-4 py-3 font-medium">E-mail</th>
                    <th class="px-4 py-3 font-medium">Telefone</th>
                    <th class="px-4 py-3 font-medium">Cidade</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $customer->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $customer->email }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $customer->phone }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $customer->city }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <button @click="menu = false; openEdit({{ $customer->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar cliente
                                    </button>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Excluir este cliente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar cliente
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Nenhum cliente cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-3">
            @forelse ($customers as $customer)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">{{ $customer->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $customer->email }}</p>
                            <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                            <p class="text-sm text-gray-500">{{ $customer->city }}</p>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
                            <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                            <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                <button @click="menu = false; openEdit({{ $customer->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar cliente
                                </button>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Excluir este cliente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar cliente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhum cliente cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $customers->links() }}</div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Cliente</h2>
                    <span class="w-5"></span>
                </div>

                <form :action="actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="customer_id" :value="customer.id">

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nome completo:</label>
                        <input type="text" name="name" x-model="customer.name" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">E-mail:</label>
                        <input type="email" name="email" x-model="customer.email" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Telefone:</label>
                        <input type="text" name="phone" x-model="customer.phone" x-mask="(99) 99999-9999" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">CPF:</label>
                        <input type="text" name="cpf" x-model="customer.cpf" x-mask="999.999.999-99" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('cpf') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Data de nascimento:</label>
                        <input type="date" name="birth_date" x-model="customer.birth_date" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Cidade:</label>
                        <input type="text" name="city" x-model="customer.city" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Observações:</label>
                        <textarea name="notes" x-model="customer.notes" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel"></textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
        const STORE_URL = @json(route('customers.store'));
        const customersData = {
            @foreach ($customers as $customer)
            "{{ $customer->id }}": {
                id: {{ $customer->id }},
                name: @json($customer->name),
                email: @json($customer->email),
                phone: @json($customer->phone),
                cpf: @json($customer->cpf),
                birth_date: @json($customer->birth_date?->format('Y-m-d')),
                city: @json($customer->city),
                notes: @json($customer->notes),
                update_url: @json(route('customers.update', $customer)),
            },
            @endforeach
        };
        const oldInput = @json(old());
        const hasErrors = @json($errors->any());

        function emptyCustomer() { return { id: '', name: '', email: '', phone: '', cpf: '', birth_date: '', city: '', notes: '' }; }

        function customerDrawer() {
            return {
                open: false,
                mode: 'create',
                customer: emptyCustomer(),
                actionUrl: STORE_URL,
                openCreate() { this.customer = emptyCustomer(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                openEdit(id) { this.customer = { ...customersData[id] }; this.actionUrl = customersData[id].update_url; this.mode = 'edit'; this.open = true; },
                close() { this.open = false; },
                init() {
                    if (!hasErrors) return;
                    const id = oldInput.customer_id;
                    const isEdit = id && customersData[id];
                    this.customer = { ...(isEdit ? customersData[id] : emptyCustomer()), ...oldInput };
                    this.actionUrl = isEdit ? customersData[id].update_url : STORE_URL;
                    this.mode = isEdit ? 'edit' : 'create';
                    this.open = true;
                },
            };
        }
    </script>
</x-app-layout>
