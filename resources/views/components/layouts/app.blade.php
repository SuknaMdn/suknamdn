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

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            font-family: "IBM Plex Sans Arabic", sans-serif;
        }
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('/api/placeholder/1920/1080');
            background-size: cover;
            background-position: center;
        }
        .gold-gradient {
            background: linear-gradient(90deg, #d4af37 0%, #f2d675 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .scroll-down {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
    </style>
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
