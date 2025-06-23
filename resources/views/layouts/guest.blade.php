<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

<div class="grid grid-cols-2 items-center">

    {{-- Background Image --}}
    <div class="w-full h-full bg-white dark:bg-gray-900 py-8 ps-8">
        <div class="rounded-3xl bg-sky-200/50 h-full"></div>
    </div>

    {{-- Content --}}
    <div class="min-h-screen flex flex-col sm:justify-center sm:items-center lg:items-start lg:ps-20 lg:pe-48 pt-6 sm:pt-0 bg-white dark:bg-gray-900">
        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</div>



</body>
</html>

