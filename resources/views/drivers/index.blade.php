<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('drivers.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-coinpel px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar motorista
            </a>

            <button type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                Filtrar
            </button>

            <form method="GET" action="{{ route('drivers.index') }}" class="ml-auto relative hidden sm:block">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar motorista"
                       class="w-56 lg:w-72 rounded-lg border-gray-300 pr-10 text-sm focus:border-coinpel focus:ring-coinpel">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <img src="{{ asset('icons/system-uicons_search.svg') }}" class="w-4 h-4" alt="Buscar">
                </button>
            </form>
        </div>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @forelse ($drivers as $driver)
                <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    {{-- Foto / inicial --}}
                    @if ($driver->profile_photo)
                        <img src="{{ asset('storage/' . $driver->profile_photo) }}" class="w-14 h-14 rounded-full object-cover shrink-0" alt="">
                    @else
                        <div class="w-14 h-14 rounded-full bg-coinpel-light flex items-center justify-center text-coinpel font-semibold text-lg shrink-0">
                            {{ strtoupper(substr($driver->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 truncate">{{ $driver->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $driver->email }}</p>
                    </div>

                    {{-- Menu "..." --}}
                    <div x-data="{ open: false }" class="relative shrink-0">
                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600 p-2">
                            <img src="{{ asset('icons/akar-icons_more-horizontal.svg') }}" class="w-5 h-5" alt="Ações">
                        </button>
                        <div x-show="open" x-cloak @click.outside="open = false"
                             class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10">
                            <a href="{{ route('drivers.edit', $driver) }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <img src="{{ asset('icons/system-uicons_create.svg') }}" class="w-4 h-4" alt=""> Editar motorista
                            </a>
                            <form method="POST" action="{{ route('drivers.destroy', $driver) }}"
                                  onsubmit="return confirm('Excluir este motorista?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <img src="{{ asset('icons/system-uicons_trash.svg') }}" class="w-4 h-4" alt=""> Deletar motorista
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">Nenhum motorista cadastrado.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $drivers->links() }}
        </div>
    </div>
</x-app-layout>
