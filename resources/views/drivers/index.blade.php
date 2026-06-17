<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button event="driver-create" label="Adicionar motorista" />
            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ $availability ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('drivers.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Situação</label>
                            <select name="availability" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todos</option>
                                <option value="available" {{ $availability === 'available' ? 'selected' : '' }}>Disponível</option>
                                <option value="on_trip" {{ $availability === 'on_trip' ? 'selected' : '' }}>Em viagem</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('drivers.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>
            <form method="GET" action="{{ route('drivers.index') }}" class="ml-auto relative hidden sm:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar motorista"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="driversDrawer()" @driver-create.window="openCreate()" class="p-6">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @forelse ($drivers as $driver)
                <div @click="openView({{ $driver->id }})"
                     class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm cursor-pointer hover:shadow-md transition">
                    @if ($driver->profile_photo)
                        <img src="{{ asset('storage/' . $driver->profile_photo) }}" class="w-14 h-14 rounded-full object-cover shrink-0" alt="">
                    @else
                        <div class="w-14 h-14 rounded-full bg-coinpel-light flex items-center justify-center text-coinpel font-semibold text-lg shrink-0">{{ strtoupper(substr($driver->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 truncate">{{ $driver->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $driver->email }}</p>
                    </div>
                    <div x-data="{ menu: false }" @click.stop class="relative shrink-0">
                        <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-2"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                        <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10">
                            <button @click="menu = false; openEdit({{ $driver->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar motorista
                            </button>
                            <form method="POST" action="{{ route('drivers.destroy', $driver) }}" onsubmit="return confirm('Excluir este motorista?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar motorista
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">Nenhum motorista cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $drivers->links() }}</div>

        {{-- DRAWER --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Motorista</h2>
                    <form x-show="mode !== 'create'" :action="driver.delete_url" method="POST" onsubmit="return confirm('Excluir este motorista?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600"><img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt="Excluir"></button>
                    </form>
                    <span x-show="mode === 'create'" class="w-5"></span>
                </div>

                {{-- VISUALIZAÇÃO --}}
                <div x-show="mode === 'view'" class="p-6 space-y-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-3">Foto de perfil</p>
                        <div class="text-center">
                            <template x-if="driver.photo_url"><img :src="driver.photo_url" class="w-32 h-32 rounded-full object-cover mx-auto" alt=""></template>
                            <template x-if="!driver.photo_url"><div class="w-32 h-32 rounded-full bg-coinpel-light flex items-center justify-center text-coinpel text-3xl font-semibold mx-auto" x-text="(driver.name||'?').charAt(0).toUpperCase()"></div></template>
                            <form :action="driver.update_photo_url" method="POST" enctype="multipart/form-data" x-ref="photoForm" class="mt-3">
                                @csrf
                                <label class="inline-block text-sm text-coinpel cursor-pointer hover:underline">
                                    Atualizar foto
                                    <input type="file" name="profile_photo" accept="image/*" class="hidden" @change="$refs.photoForm.submit()">
                                </label>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-800">Dados pessoais</h3>
                            <button @click="edit()" class="text-gray-400 hover:text-coinpel"><img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt="Editar"></button>
                        </div>
                        <dl class="space-y-1 text-sm">
                            <div class="flex gap-2"><dt class="text-gray-500">Nome:</dt><dd class="text-gray-800" x-text="driver.name"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Data de nascimento:</dt><dd class="text-gray-800" x-text="driver.birth_date_display"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Matrícula:</dt><dd class="text-gray-800" x-text="driver.registration_number"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">CPF:</dt><dd class="text-gray-800" x-text="driver.cpf"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">RG:</dt><dd class="text-gray-800" x-text="driver.rg"></dd></div>
                        </dl>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-800">Endereço</h3>
                            <button @click="edit()" class="text-gray-400 hover:text-coinpel"><img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt="Editar"></button>
                        </div>
                        <dl class="space-y-1 text-sm">
                            <div class="flex gap-2"><dt class="text-gray-500">CEP:</dt><dd class="text-gray-800" x-text="driver.postal_code"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Logradouro:</dt><dd class="text-gray-800" x-text="driver.street"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Número:</dt><dd class="text-gray-800" x-text="driver.number"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Cidade/Estado:</dt><dd class="text-gray-800"><span x-text="driver.city"></span><span x-show="driver.state">/<span x-text="driver.state"></span></span></dd></div>
                        </dl>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-800">Contato</h3>
                            <button @click="edit()" class="text-gray-400 hover:text-coinpel"><img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt="Editar"></button>
                        </div>
                        <dl class="space-y-1 text-sm">
                            <div class="flex gap-2"><dt class="text-gray-500">Email:</dt><dd class="text-gray-800" x-text="driver.email"></dd></div>
                            <div class="flex gap-2"><dt class="text-gray-500">Telefone:</dt><dd class="text-gray-800" x-text="driver.phone"></dd></div>
                        </dl>
                    </div>
                </div>

                {{-- EDIÇÃO --}}
                <form x-show="mode === 'edit' || mode === 'create'" :action="actionUrl" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="driver_id" :value="driver.id">

                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-3">Foto de perfil</p>
                        <div class="text-center">
                            <template x-if="driver.photo_url"><img :src="driver.photo_url" class="w-32 h-32 rounded-full object-cover mx-auto" alt=""></template>
                            <template x-if="!driver.photo_url"><div class="w-32 h-32 rounded-full bg-coinpel-light flex items-center justify-center text-coinpel text-3xl font-semibold mx-auto" x-text="(driver.name||'?').charAt(0).toUpperCase()"></div></template>
                            <label class="mt-3 inline-block text-sm text-coinpel cursor-pointer hover:underline">
                                Atualizar foto
                                <input type="file" name="profile_photo" accept="image/*" class="hidden"
                                       @change="if($event.target.files[0]) driver.photo_url = URL.createObjectURL($event.target.files[0])">
                            </label>
                        </div>
                        @error('profile_photo') <p class="mt-1 text-xs text-red-600 text-center">{{ $message }}</p> @enderror
                    </div>

                    <p class="text-sm font-semibold text-gray-800 pt-2">Dados pessoais</p>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nome completo:</label>
                        <input type="text" name="name" x-model="driver.name" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Data de nascimento:</label>
                        <input type="date" name="birth_date" x-model="driver.birth_date" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Matrícula:</label>
                        <input type="text" name="registration_number" x-model="driver.registration_number" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('registration_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">CPF:</label>
                        <input type="text" name="cpf" x-model="driver.cpf" x-mask="999.999.999-99" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">                        @error('cpf') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">RG:</label>
                        <input type="text" name="rg" x-model="driver.rg" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('rg') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <p class="text-sm font-semibold text-gray-800 pt-2">Endereço</p>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">CEP:</label>
                        <input type="text" name="postal_code" x-model="driver.postal_code" x-mask="99999-999" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">                        @error('postal_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Logradouro:</label>
                        <input type="text" name="street" x-model="driver.street" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('street') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Número:</label>
                        <input type="text" name="number" x-model="driver.number" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Cidade:</label>
                            <input type="text" name="city" x-model="driver.city" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Estado:</label>
                            <input type="text" name="state" maxlength="2" x-model="driver.state" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            @error('state') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-gray-800 pt-2">Contato</p>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Email:</label>
                        <input type="email" name="email" x-model="driver.email" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Telefone:</label>
                        <input type="text" name="phone" x-model="driver.phone" x-mask="(99) 99999-9999" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="mode === 'create' ? close() : (mode = 'view')" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                        <button type="submit" class="flex-1 rounded-lg bg-coinpel px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark" x-text="mode === 'create' ? 'Finalizar cadastro' : 'Salvar'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const driversData = {
            @foreach ($drivers as $driver)
            "{{ $driver->id }}": {
                id: {{ $driver->id }},
                name: @json($driver->name),
                email: @json($driver->email),
                birth_date: @json(optional($driver->birth_date)->format('Y-m-d')),
                birth_date_display: @json(optional($driver->birth_date)->format('d/m/Y')),
                registration_number: @json($driver->registration_number),
                cpf: @json($driver->cpf),
                rg: @json($driver->rg),
                phone: @json($driver->phone),
                postal_code: @json($driver->postal_code),
                street: @json($driver->street),
                number: @json($driver->number),
                city: @json($driver->city),
                state: @json($driver->state),
                photo_url: @json($driver->profile_photo ? asset('storage/' . $driver->profile_photo) : null),
                delete_url: @json(route('drivers.destroy', $driver)),
                update_url: @json(route('drivers.update', $driver)),
                update_photo_url: @json(route('drivers.updatePhoto', $driver)),
            },
            @endforeach
        };
        const STORE_URL = @json(route('drivers.store'));
        const oldInput = @json(old());
        const errorDriverId = @json($errors->any() ? (string) old('driver_id') : null);
        const hasErrors = @json($errors->any());
        const reopenDriverId = @json(session('reopen_driver'));

        function emptyDriver() {
            return { id: '', name: '', email: '', birth_date: '', birth_date_display: '', registration_number: '', cpf: '', rg: '', phone: '', postal_code: '', street: '', number: '', city: '', state: '', photo_url: null };
        }

        function driversDrawer() {
            return {
                open: false,
                mode: 'view',
                driver: {},
                actionUrl: STORE_URL,
                openView(id) { this.driver = { ...driversData[id] }; this.actionUrl = driversData[id].update_url; this.mode = 'view'; this.open = true; },
                openEdit(id) { this.driver = { ...driversData[id] }; this.actionUrl = driversData[id].update_url; this.mode = 'edit'; this.open = true; },
                openCreate() { this.driver = emptyDriver(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                edit() { this.mode = 'edit'; },
                close() { this.open = false; },
                init() {
                    if (errorDriverId && driversData[errorDriverId]) {
                        this.driver = { ...driversData[errorDriverId], ...oldInput };
                        this.actionUrl = driversData[errorDriverId].update_url;
                        this.mode = 'edit';
                        this.open = true;
                    } else if (hasErrors) {
                        this.driver = { ...emptyDriver(), ...oldInput };
                        this.actionUrl = STORE_URL;
                        this.mode = 'create';
                        this.open = true;
                    } else if (reopenDriverId && driversData[reopenDriverId]) {
                        this.openView(reopenDriverId);
                    }
                },
            };
        }
    </script>
</x-app-layout>
