<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Mobile-specific styles -->
    <style>
        /* Fix for mobile viewport height issues */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Use dynamic viewport units for better mobile support */
        .mobile-full-height {
            min-height: 100vh;
            min-height: 100dvh; /* Dynamic viewport height - better for mobile */
        }

        /* Ensure background extends to all edges on mobile */
        body {
            background-color: #f9fafb; /* bg-gray-50 equivalent */
        }

        /* Safe area padding for devices with notches */
        .safe-area-inset {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #111827; /* dark:bg-gray-900 equivalent */
            }
        }

        /* Prevent overscroll on mobile */
        .mobile-container {
            overscroll-behavior: none;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased safe-area-inset">

<!-- Desktop Layout (lg and up) -->
<div class="hidden lg:grid lg:grid-cols-2 lg:items-center min-h-screen">
    {{-- Background Image - Only shown on desktop --}}
    <div class="w-full h-full bg-white dark:bg-gray-900 py-8 ps-8">
        <div class="rounded-3xl bg-sky-200/50 h-full" style="background-image: url('../storage/background-image/background3.jpg'); object-fit: cover; background-repeat: no-repeat; background-size: cover; background-position: center">
{{--            <img class="w-full h-full object-cover rounded-3xl opacity-80" src="storage/background-image/background3.jpg" alt="background image">--}}
        </div>
    </div>

    {{-- Content - Desktop --}}
    <div class="min-h-screen flex flex-col sm:justify-center sm:items-center lg:items-start lg:ps-20 lg:pe-48 pt-6 sm:pt-0 bg-white dark:bg-gray-900">
        <div class="w-full">
            {{ $slot }}
        </div>
    </div>
</div>

<!-- Mobile Layout (below lg) -->
<div class="lg:hidden mobile-full-height bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-8 mobile-container">

    <!-- Content Slot for Mobile -->
    <div class="w-full max-w-md mx-auto">
        {{ $slot }}
    </div>

</div>

</body>
</html>
