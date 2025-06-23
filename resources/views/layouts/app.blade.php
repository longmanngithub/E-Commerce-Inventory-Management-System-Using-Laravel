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
<body class="font-sans antialiased overflow-hidden">
<!-- Global Alpine.js state for navigation -->
<div x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                console.log('Sidebar toggled:', this.sidebarOpen);
            },
            closeSidebar() {
                this.sidebarOpen = false;
                console.log('Sidebar closed:', this.sidebarOpen);
            },
            init() {
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        this.sidebarOpen = true;
                    }
                });
            }
        }" class="h-screen bg-gray-100 dark:bg-gray-900 flex overflow-hidden">

    <!-- Sidebar Navigation -->
    <div
        x-show="sidebarOpen || window.innerWidth >= 1024"
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
            <div class="lg:hidden absolute top-4 right-4 z-10">
                <button
                    @click="closeSidebar()"
                    class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Content with its own scroll -->
            <div class="flex-1 overflow-y-auto">
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
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden mt-5 mb-8">

        <!-- Page Heading - Fixed at top -->
        @isset($header)
            <header class="flex-shrink-0 bg-white dark:bg-gray-800 shadow rounded-3xl mx-4 lg:mx-12">
                <div class="min-w-max mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">

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
                    <div class="hidden sm:flex sm:items-center sm:ms-6">

                        {{-- Profile dropdown --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-1 border text-xs leading-4 font-medium rounded-md text-gray-500 dark:text-white bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 ms-4">

                                    {{-- Profile Picture --}}
                                    <div class="flex-shrink-0 me-3">
                                        <img class="h-8 w-8 rounded-md object-cover" src="{{ Auth::user()->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ Auth::user()->owner_name }}">
                                    </div>

                                    {{-- Name and Role --}}
                                    <div class="text-left">
                                        <div class="font-medium text-sm text-gray-800 dark:text-white">{{ Auth::user()->owner_name }}</div>
                                        <div class="font-medium text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->role }}</div>
                                    </div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
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

