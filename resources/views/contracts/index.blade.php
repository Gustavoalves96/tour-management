<x-app-layout>
    @php
        // status: chave (banco) => rotulo (tela) e as cores do badge
        $statuses = \App\Models\Contract::STATUSES;
        $statusColors = [
            'pending'     => 'bg-amber-100 text-amber-700',
            'confirmed'   => 'bg-blue-100 text-blue-700',
            'in_progress' => 'bg-indigo-100 text-indigo-700',
            'completed'   => 'bg-green-100 text-green-700',
            'cancelled'   => 'bg-gray-100 text-gray-600',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button event="contract-create" label="Adicionar contrato" />

            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ $status ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('contracts.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todos</option>
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('contracts.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Busca --}}
            <form method="GET" action="{{ route('contracts.index') }}" class="ml-auto relative hidden sm:block">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar por cliente"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="contractDrawer()" @contract-create.window="openCreate()" class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Cliente</th>
                    <th class="px-4 py-3 font-medium">Pacote</th>
                    <th class="px-4 py-3 font-medium">Pessoas</th>
                    <th class="px-4 py-3 font-medium">Valor total</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Período</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($contracts as $contract)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $contract->customer->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $contract->package->name }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $contract->number_of_people }}</td>
                        <td class="px-4 py-3 text-gray-800">R$ {{ number_format($contract->total_value, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$contract->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $statuses[$contract->status] ?? $contract->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-800">
                            {{ $contract->start_date->format('d/m/Y') }}@if ($contract->end_date) → {{ $contract->end_date->format('d/m/Y') }}@endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <button @click="menu = false; openEdit({{ $contract->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar contrato
                                    </button>
                                    <form method="POST" action="{{ route('contracts.destroy', $contract) }}" onsubmit="return confirm('Excluir este contrato?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar contrato
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500">Nenhum contrato cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden space-y-3">
            @forelse ($contracts as $contract)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">{{ $contract->customer->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $contract->package->name }}</p>
                            <p class="text-sm text-gray-500">{{ $contract->number_of_people }} pessoas · R$ {{ number_format($contract->total_value, 2, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">{{ $contract->start_date->format('d/m/Y') }}@if ($contract->end_date) → {{ $contract->end_date->format('d/m/Y') }}@endif</p>
                            <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$contract->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $statuses[$contract->status] ?? $contract->status }}</span>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
                            <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                            <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                <button @click="menu = false; openEdit({{ $contract->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar contrato
                                </button>
                                <form method="POST" action="{{ route('contracts.destroy', $contract) }}" onsubmit="return confirm('Excluir este contrato?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar contrato
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhum contrato cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $contracts->links() }}</div>

        {{-- DRAWER --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Contrato</h2>
                    <span class="w-5"></span>
                </div>

                <form :action="actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="contract_id" :value="contract.id">

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Cliente:</label>
                        <select name="customer_id" x-model="contract.customer_id" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            <option value="">Selecione um cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Pacote:</label>
                        <select name="package_id" x-model="contract.package_id" @change="recalc()" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            <option value="">Selecione um pacote</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }} — {{ $package->origin }} → {{ $package->destination }}</option>
                            @endforeach
                        </select>
                        @error('package_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Número de pessoas:</label>
                        <input type="number" min="1" name="number_of_people" x-model="contract.number_of_people" @input="recalcTotal()" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('number_of_people') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Valor total:</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">R$</span>
                            <input type="text" name="total_value" x-model="contract.total_value" x-mask:dynamic="$money($input, ',', '.')" inputmode="decimal"
                                   class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-coinpel focus:ring-coinpel">
                        </div>
                        @error('total_value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Status:</label>
                        <select name="status" x-model="contract.status" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Data de início:</label>
                        <input type="date" name="start_date" x-model="contract.start_date" @change="recalcEndDate()" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Data de término:</label>
                        <input type="date" name="end_date" x-model="contract.end_date" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Observações:</label>
                        <textarea name="notes" x-model="contract.notes" rows="3" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel"></textarea>
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
        const STORE_URL = @json(route('contracts.store'));

        // dados completos de cada contrato (pra preencher o drawer na edicao)
        const contractsData = {
            @foreach ($contracts as $contract)
            "{{ $contract->id }}": {
                id: {{ $contract->id }},
                customer_id: {{ $contract->customer_id }},
                package_id: {{ $contract->package_id }},
                number_of_people: {{ $contract->number_of_people }},
                total_value: "{{ number_format($contract->total_value, 2, ',', '.') }}",
                status: @json($contract->status),
                start_date: @json($contract->start_date?->format('Y-m-d')),
                end_date: @json($contract->end_date?->format('Y-m-d')),
                notes: @json($contract->notes),
                update_url: @json(route('contracts.update', $contract)),
            },
            @endforeach
        };

        // preco e duracao de cada pacote (pro auto-preenchimento)
        const packagesInfo = {
            @foreach ($packages as $package)
            "{{ $package->id }}": { price: "{{ $package->price }}", duration_days: {{ $package->duration_days }} },
            @endforeach
        };

        const oldInput = @json(old());
        const hasErrors = @json($errors->any());

        function emptyContract() {
            return { id: '', customer_id: '', package_id: '', number_of_people: '', total_value: '', status: 'pending', start_date: '', end_date: '', notes: '' };
        }

        // 1234.56 -> "1.234,56"
        function formatMoney(value) {
            if (isNaN(value)) return '';
            return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function contractDrawer() {
            return {
                open: false,
                mode: 'create',
                contract: emptyContract(),
                actionUrl: STORE_URL,
                openCreate() { this.contract = emptyContract(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                openEdit(id) { this.contract = { ...contractsData[id] }; this.actionUrl = contractsData[id].update_url; this.mode = 'edit'; this.open = true; },
                close() { this.open = false; },

                // auto-preenche valor total (preco do pacote x pessoas) - pode sobrescrever manualmente
                recalcTotal() {
                    const info = packagesInfo[this.contract.package_id];
                    const people = parseInt(this.contract.number_of_people) || 0;
                    if (!info || people <= 0) return;
                    this.contract.total_value = formatMoney(parseFloat(info.price) * people);
                },
                // auto-preenche data de termino (inicio + duracao do pacote)
                recalcEndDate() {
                    const info = packagesInfo[this.contract.package_id];
                    if (!info || !this.contract.start_date) return;
                    const start = new Date(this.contract.start_date + 'T00:00:00');
                    start.setDate(start.getDate() + (parseInt(info.duration_days) || 0));
                    this.contract.end_date = start.toISOString().slice(0, 10);
                },
                recalc() { this.recalcTotal(); this.recalcEndDate(); },

                init() {
                    if (!hasErrors) return;
                    const id = oldInput.contract_id;
                    const isEdit = id && contractsData[id];
                    this.contract = { ...(isEdit ? contractsData[id] : emptyContract()), ...oldInput };
                    // total_value vem normalizado ("1234.56") no old() -> reformata pra mascara
                    if (oldInput.total_value) this.contract.total_value = formatMoney(parseFloat(oldInput.total_value));
                    this.actionUrl = isEdit ? contractsData[id].update_url : STORE_URL;
                    this.mode = isEdit ? 'edit' : 'create';
                    this.open = true;
                },
            };
        }
    </script>
</x-app-layout>
