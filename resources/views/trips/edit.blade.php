<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Voltar</a>
    </x-slot>
    <div class="p-6">
        @include('trips.form', ['trip' => $trip])
    </div>
</x-app-layout>
