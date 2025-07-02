<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Mobile-specific styles for full-width layout -->
    <style>
        /* Fix for mobile viewport height issues */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        /* Use dynamic viewport units for better mobile support */
        .mobile-full-height {
            min-height: 100vh;
            min-height: 100dvh; /* Dynamic viewport height - better for mobile */
        }

        /* Safe area padding for devices with notches */
        .safe-area-inset {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        /* Prevent overscroll on mobile */
        body {
            overscroll-behavior: none;
            -webkit-overflow-scrolling: touch;
            background-color: #111827; /* dark:bg-gray-900 equivalent */
        }

        /* Ensure content fills full width and height */
        .full-width-container {
            width: 100vw;
            width: 100dvw; /* Dynamic viewport width */
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* Handle landscape orientation */
        @media screen and (orientation: landscape) and (max-height: 500px) {
            .mobile-full-height {
                min-height: 100vh;
            }
        }

        @media (prefers-color-scheme: light) {
            body {
                background-color: #f3f4f6; /* bg-gray-100 equivalent */
            }
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased safe-area-inset">
{{-- This layout renders content directly with mobile optimizations --}}
<div class="full-width-container">
    {{ $slot }}
</div>
</body>
</html>
