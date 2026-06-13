<h3 class="font-semibold text-gray-800 mb-2">Informações da viagem</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" value="Nome da viagem" />
        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $trip?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="rule" value="Regra" />
        <select id="rule" name="rule" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Selecione...</option>
            @foreach (\App\Models\Trip::RULES as $rule)
                <option value="{{ $rule }}" @selected(old('rule', $trip?->rule) == $rule)>{{ $rule }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('rule')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="origin" value="Origem" />
        <x-text-input id="origin" name="origin" class="block mt-1 w-full" :value="old('origin', $trip?->origin)" required />
        <x-input-error :messages="$errors->get('origin')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="destination" value="Destino" />
        <x-text-input id="destination" name="destination" class="block mt-1 w-full" :value="old('destination', $trip?->destination)" required />
        <x-input-error :messages="$errors->get('destination')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="date" value="Data" />
        <x-text-input id="date" name="date" type="date" class="block mt-1 w-full" :value="old('date', $trip?->date?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="departure_time" value="Horário de saída" />
        <x-text-input id="departure_time" name="departure_time" type="time" class="block mt-1 w-full" :value="old('departure_time', $trip ? substr($trip->departure_time, 0, 5) : '')" required />
        <x-input-error :messages="$errors->get('departure_time')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="arrival_time" value="Horário de chegada" />
        <x-text-input id="arrival_time" name="arrival_time" type="time" class="block mt-1 w-full" :value="old('arrival_time', $trip && $trip->arrival_time ? substr($trip->arrival_time, 0, 5) : '')" />
        <x-input-error :messages="$errors->get('arrival_time')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="single_ticket_price" value="Valor da passagem avulsa (R$)" />
        <x-text-input id="single_ticket_price" name="single_ticket_price" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="old('single_ticket_price', $trip?->single_ticket_price)" />
        <x-input-error :messages="$errors->get('single_ticket_price')" class="mt-2" />
    </div>
</div>

<h3 class="font-semibold text-gray-800 mb-2 mt-6">Dados do veículo</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="vehicle_id" value="Veículo" />
        <select id="vehicle_id" name="vehicle_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Selecione...</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $trip?->vehicle_id) == $vehicle->id)>
                    {{ $vehicle->prefix }} - {{ $vehicle->model }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="passengers" value="Número de passageiros" />
        <x-text-input id="passengers" name="passengers" type="number" min="1" class="block mt-1 w-full" :value="old('passengers', $trip?->passengers)" />
        <x-input-error :messages="$errors->get('passengers')" class="mt-2" />
    </div>
</div>

<h3 class="font-semibold text-gray-800 mb-2 mt-6">Motorista e status</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="driver_id" value="Motorista" />
        <select id="driver_id" name="driver_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">Selecione...</option>
            @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}" @selected(old('driver_id', $trip?->driver_id) == $driver->id)>
                    {{ $driver->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('driver_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
            @foreach (\App\Models\Trip::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $trip?->status ?? 'in_progress') == $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>
