<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo motorista</h2></x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('drivers.store') }}" enctype="multipart/form-data">
                @csrf
                @include('drivers.form', ['driver' => null])
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('drivers.index') }}" class="text-gray-600">Cancelar</a>
                    <x-primary-button>Finalizar cadastro</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
