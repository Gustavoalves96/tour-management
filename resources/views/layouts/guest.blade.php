<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'COINPEL') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">
    {{-- Formulário (ESQUERDA, branco) --}}
    <div class="flex-1 flex items-center justify-center p-6 bg-white">
        <div class="w-full max-w-[460px]">
            <div class="flex justify-center mb-10">
                <img src="{{ asset('images/logo-color.png') }}" alt="COINPEL" class="w-full">
            </div>
            {{ $slot }}
        </div>
    </div>

    {{-- Painel roxo com ilustração ancorada embaixo (DIREITA) --}}
    <div class="hidden lg:block lg:w-1/2 bg-coinpel relative overflow-hidden">
        <img src="{{ asset('images/login-bus.png') }}" alt=""
             class="absolute bottom-0 left-0 w-full">
    </div>
</div>
</body>
</html>
