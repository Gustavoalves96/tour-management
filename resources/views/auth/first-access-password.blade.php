<x-guest-layout>
    <h2 class="text-lg font-medium text-gray-900 mb-2">Crie uma nova senha</h2>
    <p class="text-sm text-gray-600 mb-4">
        No seu primeiro acesso é necessário trocar a senha provisória.
        É obrigatório que a senha tenha no mínimo 8 caracteres.
    </p>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="password" value="Nova senha" />
            <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Repetir senha" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Confirmar</x-primary-button>
        </div>
    </form>
</x-guest-layout>
