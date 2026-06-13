<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo usuário</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div>
                    <x-input-label for="name" value="Nome completo" />
                    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="E-mail" />
                    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Senha provisória" />
                    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <p class="text-sm text-gray-500 mt-1">O usuário será obrigado a trocar no primeiro acesso.</p>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-4">
                    <a href="{{ route('users.index') }}" class="text-gray-600">Cancelar</a>
                    <x-primary-button>Finalizar cadastro</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
