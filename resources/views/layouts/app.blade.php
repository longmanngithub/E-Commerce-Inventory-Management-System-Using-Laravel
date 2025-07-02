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
            overflow: hidden;
        }

        /* Use dynamic viewport units for better mobile support */
        .mobile-full-height {
            height: 100vh;
            height: 100dvh; /* Dynamic viewport height - better for mobile */
        }

        /* Ensure dark background extends to all edges on mobile */
        body {
            background-color: #111827; /* dark:bg-gray-900 equivalent */
        }

        /* Safe area padding for devices with notches */
        .safe-area-inset {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        @media (prefers-color-scheme: light) {
            body {
                background-color: #f3f4f6; /* bg-gray-100 equivalent */
            }
        }
    </style>
</head>
<body class="font-sans antialiased safe-area-inset">
<!-- Global Alpine.js state for navigation -->
<div x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            isDesktop: window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            closeSidebar() {
                this.sidebarOpen = false;
            },
            init() {
                const checkDesktop = () => {
                    this.isDesktop = window.innerWidth >= 1024;
                    if (this.isDesktop) this.sidebarOpen = true;
                    else this.sidebarOpen = false;
                };
                window.addEventListener('resize', checkDesktop);
                checkDesktop();
            }
        }" class="mobile-full-height bg-gray-100 dark:bg-gray-900 flex overflow-hidden">

    <!-- Sidebar Navigation -->
    <div
        x-show="sidebarOpen || isDesktop"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full opacity-0 lg:translate-x-0 lg:opacity-100"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0 lg:translate-x-0 lg:opacity-100"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 lg:relative lg:translate-x-0 lg:transition-all lg:duration-300 lg:ease-in-out"
        :class="sidebarOpen ? 'lg:w-72' : 'lg:w-0'"
    >
        <div class="h-full flex flex-col overflow-hidden">
            <!-- Mobile Close Button (only visible on mobile) -->
            <div
                class="lg:hidden absolute top-1/2 right-[-60px] transform -translate-y-1/2 z-50"
            >
                <button
                    @click="closeSidebar()"
                    class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 shadow-md flex items-center justify-center text-gray-600 dark:text-white transition"
                    aria-label="Close sidebar"
                >
                    <!-- Left Arrow Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Content with its own scroll -->
            <div class="lg:hidden flex-1 overflow-y-auto mt-32">
                @include('layouts.navigation')
            </div>

            <div class="hidden lg:block flex-1 overflow-y-auto">
                @include('layouts.navigation')
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closeSidebar()"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden z-40"
    ></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden pb-1 lg:pb-6">

        <!-- Page Heading - Fixed at top -->
        @isset($header)
            <header class="sticky z-30 bg-white dark:bg-gray-800 shadow rounded-3xl mx-4 lg:mx-12 top-3 lg:top-6">
                <div class="min-w-max mx-auto p-4 sm:px-6 lg:px-8 flex justify-between items-center">

                    <div class="flex items-center justify-between">

                        <!-- Hamburger Button -->
                        <div class="flex items-center me-3">
                            <button
                                @click="sidebarOpen = !sidebarOpen"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }"
                                        class="inline-flex"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }"
                                        class="hidden"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        {{ $header }}

                    </div>

                    <!-- Settings Dropdown -->
                    <div class="flex flex-wrap items-center justify-end ms-4">

                        {{-- Profile dropdown --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 lg:px-3 py-1 border text-xs leading-4 font-medium rounded-md text-gray-500 dark:text-white bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 ms-4">

                                    {{-- Profile Picture (Always visible) --}}
                                    @if(Auth::user()->image_url)
                                        <div class="flex-shrink-0">
                                            <img class="h-8 w-8 rounded-md object-cover" src="{{ Auth::user()->image_url }}" alt="{{ Auth::user()->name }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500 rounded-md object-cover" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Name and Role (Only visible on desktop) --}}
                                    <div class="hidden sm:block text-left ms-3">
                                        <div class="font-medium text-sm text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                                        <div class="font-medium text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->role }}</div>
                                    </div>

                                    {{-- Arrow icon --}}
                                    <div class="text-gray-900 dark:text-white ms-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 10l5 5m0 0l5-5" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- This checks which user is logged in and generates the correct route --}}
                                <x-dropdown-link :href="route('owner.profile.edit')">
                                    {{ __('Account Settings') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>

                    </div>

                </div>
            </header>
        @endisset

        <!-- Page Content - Scrollable area -->
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>

    </div>

</div>

@stack('scripts')

</body>
</html>
