<div class="mb-6">
    <x-input-label value="Foto de perfil" />
    @if ($driver?->profile_photo)
        <img src="{{ asset('storage/'.$driver->profile_photo) }}" class="w-20 h-20 rounded-full object-cover my-2">
    @endif
    <input type="file" name="profile_photo" accept="image/*" class="block mt-1 text-sm text-gray-700">
    <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
</div>

<h3 class="font-semibold text-gray-800 mb-2">Dados pessoais</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" value="Nome completo" />
        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $driver?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="birth_date" value="Data de nascimento" />
        <x-text-input id="birth_date" name="birth_date" type="date" class="block mt-1 w-full" :value="old('birth_date', $driver?->birth_date?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="registration_number" value="Matrícula" />
        <x-text-input id="registration_number" name="registration_number" class="block mt-1 w-full" :value="old('registration_number', $driver?->registration_number)" required />
        <x-input-error :messages="$errors->get('registration_number')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="cpf" value="CPF" />
        <x-text-input id="cpf" name="cpf" class="block mt-1 w-full" :value="old('cpf', $driver?->cpf)" required />
        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="rg" value="RG" />
        <x-text-input id="rg" name="rg" class="block mt-1 w-full" :value="old('rg', $driver?->rg)" />
        <x-input-error :messages="$errors->get('rg')" class="mt-2" />
    </div>
</div>

<h3 class="font-semibold text-gray-800 mb-2 mt-6">Endereço</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="postal_code" value="CEP" />
        <x-text-input id="postal_code" name="postal_code" class="block mt-1 w-full" :value="old('postal_code', $driver?->postal_code)" required />
        <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="street" value="Logradouro" />
        <x-text-input id="street" name="street" class="block mt-1 w-full" :value="old('street', $driver?->street)" required />
        <x-input-error :messages="$errors->get('street')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="number" value="Número" />
        <x-text-input id="number" name="number" class="block mt-1 w-full" :value="old('number', $driver?->number)" required />
        <x-input-error :messages="$errors->get('number')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="city" value="Cidade" />
        <x-text-input id="city" name="city" class="block mt-1 w-full" :value="old('city', $driver?->city)" required />
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="state" value="Estado (UF)" />
        <x-text-input id="state" name="state" maxlength="2" class="block mt-1 w-full" :value="old('state', $driver?->state)" required />
        <x-input-error :messages="$errors->get('state')" class="mt-2" />
    </div>
</div>

<h3 class="font-semibold text-gray-800 mb-2 mt-6">Contato</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="email" value="E-mail" />
        <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $driver?->email)" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="phone" value="Telefone" />
        <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $driver?->phone)" required />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>
</div>
