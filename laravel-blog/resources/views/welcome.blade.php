<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Blog Tutorial</title>

    <!-- Includerea fișierelor CSS și JS ale aplicației Laravel, procesate de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Includerea stilurilor necesare pentru Livewire -->
    @livewireStyles
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Laravel Blog Tutorial - Day 1</h1>
        
        <!-- Includerea componentei Livewire 'hello-world' -->
        @livewire('hello-world')
    </div>

    <!-- Includerea scripturilor necesare pentru Livewire -->
    @livewireScripts
</body>
</html>