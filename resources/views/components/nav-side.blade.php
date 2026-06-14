@props(['active' => false, 'href' => null, 'disabled' => false])

@if ($disabled)
    {{-- Placeholder: módulo do Figma ainda não implementado --}}
    <div class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white opacity-50 cursor-not-allowed select-none">
        {{ $slot }}
    </div>
@else
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition '
             . ($active ? 'bg-white/15 text-white' : 'text-white/80 hover:bg-white/10')]) }}>
        {{ $slot }}
    </a>
@endif
