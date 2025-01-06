<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Security headers -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <title>{{ config('app.name') }} - @yield('title')</title>

    <!-- Preload critical assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600&display=swap">

    <!-- Alpine.js hide -->
    <style>[x-cloak] { display: none !important; }</style>

    @filamentStyles
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="antialiased min-h-screen">
    {{ $slot }}

    @livewire('notifications')

    <!-- Scripts -->
    @filamentScripts
    @vite(['resources/js/app.js'])
    @vite(['resources/js/session-expired.js'])
    @livewireScripts
</body>
</html>
