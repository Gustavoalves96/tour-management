@php $trip = $trip ?? null; @endphp

<div x-data="tripForm()" class="max-w-5xl mx-auto">
    {{-- Título da rota --}}
    <h1 class="flex items-center justify-center gap-3 text-xl text-gray-700 mb-6">
        <span x-text="origin || 'Origem'"></span>
        <img src="{{ asset('icons/seta_direita.svg') }}" class="h-3.5" alt="›">
        <span x-text="destination || 'Destino'"></span>
    </h1>

    <form action="{{ $trip ? route('trips.update', $trip) : route('trips.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6 md:p-8">
        @csrf
        @if($trip) @method('PUT') @endif

        {{-- Status (pílula colorida com dropdown) --}}
        <div class="relative inline-block mb-6" @click.outside="statusOpen = false">
            <button type="button" @click="statusOpen = !statusOpen" :class="statuses[status].classes"
                    class="inline-flex items-center gap-3 rounded-md px-3 py-1.5 text-sm text-white">
                <span x-text="statuses[status].label"></span>
                <span class="border-l border-white/40 pl-2"><img src="{{ asset('icons/vector2.svg') }}" class="w-3" alt=""></span>
            </button>
            <div x-show="statusOpen" x-cloak class="absolute left-0 mt-1 z-10 space-y-1">
                <template x-for="(s, key) in statuses" :key="key">
                    <button type="button" @click="status = key; statusOpen = false" :class="s.classes"
                            class="flex w-44 items-center justify-between rounded-md px-3 py-1.5 text-sm text-white">
                        <span x-text="s.label"></span>
                        <img src="{{ asset('icons/vector2.svg') }}" class="w-3" alt="">
                    </button>
                </template>
            </div>
        </div>
        <input type="hidden" name="status" :value="status">

        {{-- Informações da viagem --}}
        <h2 class="text-base font-medium text-gray-700 mb-4">Informações da viagem:</h2>

        <div class="mb-4">
            <label class="block text-sm text-gray-500 mb-1">Nome da viagem:</label>
            <input type="text" name="name" value="{{ old('name', $trip->name ?? '') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Regra:</label>
                <select name="rule" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                    <option value="">Selecione</option>
                    @foreach(\App\Models\Trip::RULES as $rule)
                        <option value="{{ $rule }}" {{ old('rule', $trip->rule ?? '') === $rule ? 'selected' : '' }}>{{ $rule }}</option>
                    @endforeach
                </select>
                @error('rule') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Data:</label>
                <input type="date" name="date" value="{{ old('date', isset($trip) && $trip->date ? \Carbon\Carbon::parse($trip->date)->format('Y-m-d') : '') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Horário de Saída:</label>
                <input type="time" name="departure_time" value="{{ old('departure_time', isset($trip) && $trip->departure_time ? \Carbon\Carbon::parse($trip->departure_time)->format('H:i') : '') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                @error('departure_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Horário de Chegada:</label>
                <input type="time" name="arrival_time" value="{{ old('arrival_time', isset($trip) && $trip->arrival_time ? \Carbon\Carbon::parse($trip->arrival_time)->format('H:i') : '') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                @error('arrival_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Origem:</label>
                <input type="text" name="origin" x-model="origin" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                @error('origin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Destino:</label>
                <input type="text" name="destination" x-model="destination" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                @error('destination') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Valor da passagem avulsa:</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                    <input type="text" name="single_ticket_price" x-mask:dynamic="$money($input, ',', '.')"
                           value="{{ old('single_ticket_price', isset($trip) && $trip->single_ticket_price !== null ? number_format($trip->single_ticket_price, 2, ',', '.') : '') }}"
                           class="w-full rounded-lg border-gray-300 pl-9 text-sm focus:border-coinpel focus:ring-coinpel">
                </div>
                @error('single_ticket_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Dados do veículo --}}
        <h2 class="text-base font-medium text-gray-700 mt-8 mb-4">Dados do veículo:</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Veículo:</label>
                <select name="vehicle_id" x-model="vehicleId" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                    <option value="">Selecione</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}">{{ $v->prefix }} - {{ $v->model }}</option>
                    @endforeach
                </select>
                @error('vehicle_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Número de passageiros:</label>
                <input type="text" name="passengers" :value="vehiclesCapacity[vehicleId] ?? ''" readonly
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-500">
            </div>
        </div>

        {{-- Motorista --}}
        <h2 class="text-base font-medium text-gray-700 mt-8 mb-4">Motorista:</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Nome:</label>
                <select name="driver_id" x-model="driverId" class="w-full rounded-lg border-gray-300 text-sm focus:border-coinpel focus:ring-coinpel">
                    <option value="">Selecione</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                @error('driver_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Matrícula:</label>
                <input type="text" :value="driversRegistration[driverId] ?? ''" readonly
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-500">
            </div>
        </div>

        {{-- Botões --}}
        <div class="flex items-center gap-3 mt-10">
            <button type="submit" class="rounded-lg bg-coinpel px-6 py-2.5 text-sm font-semibold text-white hover:bg-coinpel-dark">
                {{ $trip ? 'Salvar alterações' : 'Salvar' }}
            </button>
            <a href="{{ route('trips.index') }}" class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<script>
    window.vehiclesCapacity = {
        @foreach($vehicles as $v) "{{ $v->id }}": @json($v->capacity), @endforeach
    };
    window.driversRegistration = {
        @foreach($drivers as $d) "{{ $d->id }}": @json($d->registration_number), @endforeach
    };
    function tripForm() {
        return {
            status: @json(old('status', $trip->status ?? 'in_progress')),
            statusOpen: false,
            origin: @json(old('origin', $trip->origin ?? '')),
            destination: @json(old('destination', $trip->destination ?? '')),
            vehicleId: @json((string) old('vehicle_id', $trip->vehicle_id ?? '')),
            driverId: @json((string) old('driver_id', $trip->driver_id ?? '')),
            statuses: {
                in_progress: { label: 'Em andamento', classes: 'bg-amber-400' },
                completed:   { label: 'Concluída',     classes: 'bg-green-500' },
                cancelled:   { label: 'Cancelada',     classes: 'bg-red-400' },
            },
        };
    }
</script>
