<x-guest-layout>
    <div class="relative bg-white rounded-lg border border-gray-200 shadow-xl p-6">
        {{-- X: cancelar e voltar pro login (RF06: não entra sem trocar) --}}
        <form method="POST" action="{{ route('logout') }}" class="absolute top-4 right-4">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-gray-600" title="Cancelar e sair">
                <img src="{{ asset('icons/x.svg') }}" class="w-3.5" alt="Fechar">
            </button>
        </form>

        <h2 class="text-base font-semibold text-gray-800">Crie uma nova senha</h2>
        <p class="mt-1 text-xs text-gray-500">No seu primeiro acesso é necessário trocar a senha provisória. É obrigatório que a senha tenha no mínimo 8 caracteres.</p>

        <form method="POST" action="{{ route('password.change.update') }}" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nova senha</label>
                <input id="password" type="password" name="password" required autofocus
                       class="w-full rounded-md border-gray-300 focus:border-coinpel focus:ring-coinpel">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Repetir senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full rounded-md border-gray-300 focus:border-coinpel focus:ring-coinpel">
            </div>

            <button type="submit"
                    class="w-full rounded-md bg-coinpel px-4 py-2.5 text-sm font-semibold text-white hover:bg-coinpel-dark transition">
                Confirmar
            </button>
        </form>
    </div>
</x-guest-layout>
