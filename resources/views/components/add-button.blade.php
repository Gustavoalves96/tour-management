@props(['label', 'event' => null, 'href' => null])

@php
    // mesma aparencia nos dois casos (botao-drawer ou link)
    $classes = 'inline-flex items-center gap-1.5 rounded-lg bg-coinpel px-3 xs:px-4 py-2 text-sm font-semibold text-white hover:bg-coinpel-dark whitespace-nowrap';
@endphp

@if ($href)
    {{-- caso Viagens: navega pra uma pagina de cadastro --}}
    <a href="{{ $href }}" class="{{ $classes }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden xs:inline">{{ $label }}</span>
    </a>
@else
    {{-- demais modulos: abre o drawer via CustomEvent --}}
    <button type="button" onclick="window.dispatchEvent(new CustomEvent('{{ $event }}'))" class="{{ $classes }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden xs:inline">{{ $label }}</span>
    </button>
@endif
