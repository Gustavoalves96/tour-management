<x-guest-layout>
    <h1 class="text-lg font-semibold text-gray-800 mb-4">Faça login:</h1>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="E-mail"
                   class="w-full rounded-md border-gray-300 focus:border-coinpel focus:ring-coinpel">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <input id="password" type="password" name="password" required placeholder="Senha"
                   class="w-full rounded-md border-gray-300 focus:border-coinpel focus:ring-coinpel">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-md bg-coinpel px-4 py-2.5 text-sm font-semibold text-white hover:bg-coinpel-dark transition">
            Entrar
        </button>
    </form>
</x-guest-layout>
