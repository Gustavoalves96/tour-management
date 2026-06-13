<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar usuário</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Nome completo" />
                    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $user->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="E-mail" />
                    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $user->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" value="Nova senha (opcional)" />
                    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <p class="text-sm text-gray-500 mt-1">Deixe em branco para manter a senha atual.</p>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-4">
                    <a href="{{ route('users.index') }}" class="text-gray-600">Cancelar</a>
                    <x-primary-button>Salvar alterações</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
