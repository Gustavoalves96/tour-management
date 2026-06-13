<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar motorista</h2></x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('drivers.update', $driver) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('drivers.form', ['driver' => $driver])
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('drivers.index') }}" class="text-gray-600">Cancelar</a>
                    <x-primary-button>Salvar alterações</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
