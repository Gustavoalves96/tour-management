<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <x-add-button event="vehicle-create" label="Adicionar veículo" />

            {{-- Filtrar --}}
            <div x-data="{ filterOpen: false }" class="relative">
                <button type="button" @click="filterOpen = !filterOpen"
                        class="rounded-lg border px-4 py-2 text-sm whitespace-nowrap hover:bg-gray-50 {{ $seatType ? 'border-coinpel text-coinpel' : 'border-gray-300 text-gray-700' }}">
                    Filtrar
                </button>
                <div x-show="filterOpen" x-cloak @click.outside="filterOpen = false"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 p-4 z-30">
                    <form method="GET" action="{{ route('vehicles.index') }}" class="space-y-3">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Bancada</label>
                            <select name="seat_type" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                                <option value="">Todas</option>
                                @foreach(['Convencional', 'Executivo', 'Semi-Leito', 'Leito'] as $option)
                                    <option value="{{ $option }}" {{ $seatType === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 rounded-lg bg-coinpel px-3 py-2 text-sm font-medium text-white hover:bg-coinpel-dark">Aplicar</button>
                            <a href="{{ route('vehicles.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Busca --}}
            <form method="GET" action="{{ route('vehicles.index') }}" class="ml-auto relative hidden sm:block">
                <input type="hidden" name="seat_type" value="{{ $seatType }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar veículo"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2"><img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar"></button>
            </form>
        </div>
    </x-slot>

    <div x-data="vehiclesDrawer()" @vehicle-create.window="openCreate()" class="p-6">
        <div class="hidden lg:block bg-white rounded-xl border border-gray-200">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-4 py-3 font-medium">Prefixo</th>
                    <th class="px-4 py-3 font-medium">Placa</th>
                    <th class="px-4 py-3 font-medium">Modelo</th>
                    <th class="px-4 py-3 font-medium">Chassi</th>
                    <th class="px-4 py-3 font-medium">Tipo de veículo</th>
                    <th class="px-4 py-3 font-medium">Capacidade</th>
                    <th class="px-4 py-3 font-medium">Ano</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->prefix }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->plate }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->model }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->chassis }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->type }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->capacity }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $vehicle->year }}</td>
                        <td class="px-4 py-3 text-right">
                            <div x-data="{ menu: false }" class="relative inline-block">
                                <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                                <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                    <button @click="menu = false; openEdit({{ $vehicle->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar veículo
                                    </button>
                                    <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Excluir este veículo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                            <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar veículo
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">Nenhum veículo cadastrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Cards (mobile) --}}
        <div class="lg:hidden space-y-3">
            @forelse ($vehicles as $vehicle)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800">{{ $vehicle->prefix }} · {{ $vehicle->plate }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ $vehicle->model }} — {{ $vehicle->type }}</p>
                        </div>
                        <div x-data="{ menu: false }" class="relative shrink-0">
                            <button @click="menu = !menu" class="text-gray-400 hover:text-gray-600 p-1"><img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações"></button>
                            <div x-show="menu" x-cloak @click.outside="menu = false" class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                <button @click="menu = false; openEdit({{ $vehicle->id }})" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar veículo
                                </button>
                                <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Excluir este veículo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar veículo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-y-1 text-sm text-gray-700">
                        <div><span class="text-gray-400">Chassi:</span> {{ $vehicle->chassis }}</div>
                        <div><span class="text-gray-400">Capacidade:</span> {{ $vehicle->capacity }}</div>
                        <div><span class="text-gray-400">Ano:</span> {{ $vehicle->year }}</div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">Nenhum veículo cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $vehicles->links() }}</div>

        {{-- DRAWER --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50">
            <div @click="close()" class="absolute inset-0 bg-black/40"></div>
            <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl overflow-y-auto flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600"><img src="{{ asset('icons/system-uicons_cross.svg') }}" class="w-5 h-5" alt="Fechar"></button>
                    <h2 class="font-semibold text-gray-800">Veículo</h2>
                    <form x-show="mode === 'edit'" :action="vehicle.delete_url" method="POST" onsubmit="return confirm('Excluir este veículo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600"><img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt="Excluir"></button>
                    </form>
                    <span x-show="mode !== 'edit'" class="w-5"></span>
                </div>

                <form :action="actionUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="vehicle_id" :value="vehicle.id">

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Prefixo:</label>
                        <input type="text" name="prefix" x-model="vehicle.prefix" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('prefix') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Nome de identificação:</label>
                        <input type="text" name="identification_name" x-model="vehicle.identification_name" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('identification_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Placa:</label>
                        <input type="text" name="plate" x-model="vehicle.plate" x-mask="aaa-9999" class="w-full rounded-lg border-gray-300 text-sm uppercase focus:border-coinpel focus:ring-coinpel">
                        @error('plate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Modelo:</label>
                        <input type="text" name="model" x-model="vehicle.model" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('model') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Chassi:</label>
                        <input type="text" name="chassis" x-model="vehicle.chassis" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('chassis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Capacidade:</label>
                        <input type="number" name="capacity" x-model="vehicle.capacity" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('capacity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Tipo de ônibus:</label>
                        <input type="text" name="type" x-model="vehicle.type" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Bancada:</label>
                        <select name="seat_type" x-model="vehicle.seat_type" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                            <option value="">Selecione</option>
                            <option value="Convencional">Convencional</option>
                            <option value="Executivo">Executivo</option>
                            <option value="Semi-Leito">Semi-Leito</option>
                            <option value="Leito">Leito</option>
                        </select>
                        @error('seat_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Ano:</label>
                        <input type="number" name="year" x-model="vehicle.year" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                        @error('year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Opcionais --}}
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button type="button" @click="vehicle.has_internet = !vehicle.has_internet" :class="vehicle.has_internet ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/wifi.svg') }}" class="w-4 h-4" :class="vehicle.has_internet ? '' : 'opacity-50'" alt=""> Internet
                        </button>
                        <button type="button" @click="vehicle.has_wc = !vehicle.has_wc" :class="vehicle.has_wc ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/la_toilet.svg') }}" class="w-4 h-4" :class="vehicle.has_wc ? '' : 'opacity-50'" alt=""> WC
                        </button>
                        <button type="button" @click="vehicle.has_power_outlet = !vehicle.has_power_outlet" :class="vehicle.has_power_outlet ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/mdi_power-plug-outline.svg') }}" class="w-4 h-4" :class="vehicle.has_power_outlet ? '' : 'opacity-50'" alt=""> Tomada
                        </button>
                        <button type="button" @click="vehicle.has_air_conditioning = !vehicle.has_air_conditioning" :class="vehicle.has_air_conditioning ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/mdi_air-filter.svg') }}" class="w-4 h-4" :class="vehicle.has_air_conditioning ? '' : 'opacity-50'" alt=""> Ar Condicionado
                        </button>
                        <button type="button" @click="vehicle.has_fridge = !vehicle.has_fridge" :class="vehicle.has_fridge ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/ic_outline-local-drink.svg') }}" class="w-4 h-4" :class="vehicle.has_fridge ? '' : 'opacity-50'" alt=""> Geladeira
                        </button>
                        <button type="button" @click="vehicle.has_heating = !vehicle.has_heating" :class="vehicle.has_heating ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/la_fire-alt.svg') }}" class="w-4 h-4" :class="vehicle.has_heating ? '' : 'opacity-50'" alt=""> Calefação
                        </button>
                    </div>
                    <div class="flex justify-center">
                        <button type="button" @click="vehicle.has_video = !vehicle.has_video" :class="vehicle.has_video ? 'border-coinpel bg-coinpel-light text-coinpel' : 'border-gray-300 text-gray-600'" class="flex w-1/2 items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm">
                            <img src="{{ asset('icons/video.svg') }}" class="w-4 h-4" :class="vehicle.has_video ? '' : 'opacity-50'" alt=""> Vídeo
                        </button>
                    </div>

                    <input type="hidden" name="has_internet" :value="vehicle.has_internet ? 1 : 0">
                    <input type="hidden" name="has_wc" :value="vehicle.has_wc ? 1 : 0">
                    <input type="hidden" name="has_power_outlet" :value="vehicle.has_power_outlet ? 1 : 0">
                    <input type="hidden" name="has_air_conditioning" :value="vehicle.has_air_conditioning ? 1 : 0">
                    <input type="hidden" name="has_fridge" :value="vehicle.has_fridge ? 1 : 0">
                    <input type="hidden" name="has_heating" :value="vehicle.has_heating ? 1 : 0">
                    <input type="hidden" name="has_video" :value="vehicle.has_video ? 1 : 0">

                    <div class="space-y-2 pt-4">
                        <button type="submit" class="w-full rounded-lg bg-coinpel px-4 py-2.5 text-sm font-semibold text-white hover:bg-coinpel-dark" x-text="mode === 'edit' ? 'Salvar' : 'Finalizar cadastro'"></button>
                        <button type="button" @click="close()" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const STORE_URL = @json(route('vehicles.store'));
        const vehiclesData = {
            @foreach ($vehicles as $vehicle)
            "{{ $vehicle->id }}": {
                id: {{ $vehicle->id }},
                prefix: @json($vehicle->prefix),
                identification_name: @json($vehicle->identification_name),
                plate: @json($vehicle->plate),
                model: @json($vehicle->model),
                chassis: @json($vehicle->chassis),
                capacity: @json($vehicle->capacity),
                type: @json($vehicle->type),
                seat_type: @json($vehicle->seat_type),
                year: @json($vehicle->year),
                has_internet: @json((bool) $vehicle->has_internet),
                has_wc: @json((bool) $vehicle->has_wc),
                has_power_outlet: @json((bool) $vehicle->has_power_outlet),
                has_air_conditioning: @json((bool) $vehicle->has_air_conditioning),
                has_fridge: @json((bool) $vehicle->has_fridge),
                has_heating: @json((bool) $vehicle->has_heating),
                has_video: @json((bool) $vehicle->has_video),
                update_url: @json(route('vehicles.update', $vehicle)),
                delete_url: @json(route('vehicles.destroy', $vehicle)),
            },
            @endforeach
        };
        const oldInput = @json(old());
        const hasErrors = @json($errors->any());

        function emptyVehicle() {
            return { id: '', prefix: '', identification_name: '', plate: '', model: '', chassis: '', capacity: '', type: '', seat_type: '', year: '',
                has_internet: false, has_wc: false, has_power_outlet: false, has_air_conditioning: false, has_fridge: false, has_heating: false, has_video: false };
        }
        function coerceOld(old) {
            const amenities = ['has_internet','has_wc','has_power_outlet','has_air_conditioning','has_fridge','has_heating','has_video'];
            const out = { ...old };
            amenities.forEach(k => out[k] = (old[k] === '1' || old[k] === 1 || old[k] === true));
            return out;
        }

        function vehiclesDrawer() {
            return {
                open: false,
                mode: 'create',
                vehicle: emptyVehicle(),
                actionUrl: STORE_URL,
                openCreate() { this.vehicle = emptyVehicle(); this.actionUrl = STORE_URL; this.mode = 'create'; this.open = true; },
                openEdit(id) { this.vehicle = { ...vehiclesData[id] }; this.actionUrl = vehiclesData[id].update_url; this.mode = 'edit'; this.open = true; },
                close() { this.open = false; },
                init() {
                    if (hasErrors) {
                        const id = oldInput.vehicle_id;
                        if (id && vehiclesData[id]) {
                            this.vehicle = { ...vehiclesData[id], ...coerceOld(oldInput) };
                            this.actionUrl = vehiclesData[id].update_url;
                            this.mode = 'edit';
                        } else {
                            this.vehicle = { ...emptyVehicle(), ...coerceOld(oldInput) };
                            this.actionUrl = STORE_URL;
                            this.mode = 'create';
                        }
                        this.open = true;
                    }
                },
            };
        }
    </script>
</x-app-layout>
