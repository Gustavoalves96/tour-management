<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="prefix" value="Prefixo" />
        <x-text-input id="prefix" name="prefix" class="block mt-1 w-full" :value="old('prefix', $vehicle?->prefix)" required autofocus />
        <x-input-error :messages="$errors->get('prefix')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="identification_name" value="Nome de identificação" />
        <x-text-input id="identification_name" name="identification_name" class="block mt-1 w-full" :value="old('identification_name', $vehicle?->identification_name)" required />
        <x-input-error :messages="$errors->get('identification_name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="plate" value="Placa" />
        <x-text-input id="plate" name="plate" class="block mt-1 w-full" :value="old('plate', $vehicle?->plate)" required />
        <x-input-error :messages="$errors->get('plate')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="model" value="Modelo" />
        <x-text-input id="model" name="model" class="block mt-1 w-full" :value="old('model', $vehicle?->model)" required />
        <x-input-error :messages="$errors->get('model')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="chassis" value="Chassi" />
        <x-text-input id="chassis" name="chassis" class="block mt-1 w-full" :value="old('chassis', $vehicle?->chassis)" required />
        <x-input-error :messages="$errors->get('chassis')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="capacity" value="Capacidade" />
        <x-text-input id="capacity" name="capacity" type="number" min="1" class="block mt-1 w-full" :value="old('capacity', $vehicle?->capacity)" required />
        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="type" value="Tipo de veículo" />
        <x-text-input id="type" name="type" class="block mt-1 w-full" :value="old('type', $vehicle?->type)" placeholder="Ônibus, Van..." required />
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="seat_type" value="Bancada" />
        <x-text-input id="seat_type" name="seat_type" class="block mt-1 w-full" :value="old('seat_type', $vehicle?->seat_type)" placeholder="Semi-Leito, Leito..." />
        <x-input-error :messages="$errors->get('seat_type')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="year" value="Ano" />
        <x-text-input id="year" name="year" type="number" class="block mt-1 w-full" :value="old('year', $vehicle?->year)" required />
        <x-input-error :messages="$errors->get('year')" class="mt-2" />
    </div>
</div>

<div class="mt-6">
    <span class="block font-medium text-sm text-gray-700 mb-2">Opcionais</span>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        @foreach ([
            'has_internet' => 'Internet',
            'has_wc' => 'WC',
            'has_power_outlet' => 'Tomada',
            'has_air_conditioning' => 'Ar Condicionado',
            'has_fridge' => 'Geladeira',
            'has_heating' => 'Calefação',
            'has_video' => 'Vídeo',
        ] as $field => $label)
            <label class="inline-flex items-center">
                <input type="checkbox" name="{{ $field }}" value="1"
                       @checked(old($field, $vehicle?->$field))
                       class="rounded border-gray-300 text-indigo-600">
                <span class="ms-2 text-sm text-gray-700">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>
