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
            sidebarOpen: window.innerWidth >= 1280,
            isDesktop: window.innerWidth >= 1280,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            },
            closeSidebar() {
                this.sidebarOpen = false;
            },
            init() {
                const checkDesktop = () => {
                    this.isDesktop = window.innerWidth >= 1280;
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
        class="fixed inset-0 lg:inset-y-0 lg:left-0 lg:right-auto z-50 w-72 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 lg:relative lg:translate-x-0 lg:transition-all lg:duration-300 lg:ease-in-out"
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
            <div class="lg:hidden flex-1 overflow-y-auto">
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
        class="fixed inset-0 bg-gray-100 dark:bg-gray-600 bg-opacity-75 lg:hidden z-40"
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

                        {{-- Export Button (only show on analytics page) --}}
                        @if(request()->routeIs('analytics.index'))
                            <form action="{{ route('analytics.export') }}" method="GET" class="mr-4">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <!-- Export icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="26" height="26" viewBox="0 0 512 512">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M336 192h40a40 40 0 0 1 40 40v192a40 40 0 0 1-40 40H136a40 40 0 0 1-40-40V232a40 40 0 0 1 40-40h40m160-64l-80-80l-80 80m80 193V48" />
                                    </svg>
                                    Export
                                </button>
                            </form>
                        @endif

                        {{-- Notification --}}
                        <div x-data="{
                                open: false,
                                notifications: [],
                                unreadCount: 0,
                                fetchNotifications() {
                                    fetch('{{ config('services.api.url') }}/notifications', {
                                        headers: { 'Authorization': 'Bearer ' + '{{ session('api_token') }}', 'Accept': 'application/json' }
                                    })
                                    .then(res => res.json())
                                    .then(data => { this.notifications = data; this.unreadCount = data.length; });
                                },
                                markAsRead(notificationId) {
                                    fetch('{{ config('services.api.url') }}/notifications/' + notificationId + '/mark-as-read', {
                                        method: 'POST',
                                        headers: {
                                            'Authorization': 'Bearer ' + '{{ session('api_token') }}',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                    });
                                }
                             }"
                             x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)"
                             class="relative">

                            {{-- Bell Icon with unread count badge --}}
                            <button @click="open = !open" class="relative p-2 dark:text-white bg-gray-200 dark:bg-gray-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="-3 -2 30 30"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="M5.158 11.491c-.073 1.396.011 2.882-1.236 3.817A2.3 2.3 0 0 0 3 17.153C3 18.15 3.782 19 4.8 19h14.4c1.018 0 1.8-.85 1.8-1.847c0-.726-.342-1.41-.922-1.845c-1.247-.935-1.163-2.421-1.236-3.817a6.851 6.851 0 0 0-13.684 0"/><path d="M10.5 3.125C10.5 3.953 11.172 5 12 5s1.5-1.047 1.5-1.875S12.828 2 12 2s-1.5.297-1.5 1.125M15 19a3 3 0 1 1-6 0"/></g></svg>
                                <span x-show="unreadCount > 0" class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center" x-text="unreadCount"></span>
                            </button>

                            {{-- Mobile Full-Screen Overlay --}}
                            <div x-show="open"
                                 x-transition:enter="transition-opacity ease-linear duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity ease-linear duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-black bg-opacity-50 z-50 lg:hidden"
                                 @click="open = false">
                            </div>

                            {{-- Mobile Full-Screen Notification Panel --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="translate-y-full"
                                 x-transition:enter-end="translate-y-0"
                                 x-transition:leave="transition ease-in duration-300 transform"
                                 x-transition:leave-start="translate-y-0"
                                 x-transition:leave-end="translate-y-full"
                                 class="fixed inset-x-0 bottom-0 top-0 bg-white dark:bg-gray-800 z-50 flex flex-col lg:hidden"
                                 @click.stop>

                                {{-- Header with close button --}}
                                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h2>
                                    <button @click="open = false" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Scrollable notification list --}}
                                <div class="flex-1 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <div class="flex items-start justify-between">
                                                    <a :href="'{{ url('/') }}' + notification.data.link"
                                                       class="flex-1 text-gray-800 dark:text-gray-200"
                                                       @click="open = false">
                                                        <p class="text-sm font-medium mb-1" x-text="notification.data.message"></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="new Date(notification.created_at).toLocaleString()"></p>
                                                    </a>
                                                    <button @click="markAsRead(notification.id)"
                                                            class="ml-3 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex-shrink-0"
                                                            title="Mark as read">
                                                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Empty state --}}
                                    <div x-show="unreadCount === 0" class="flex flex-col items-center justify-center py-12 px-4">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 8h16M4 16h8"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-center">No new notifications</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Desktop Dropdown Menu (unchanged) --}}
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg dark:bg-gray-700 z-50 hidden lg:block">
                                <div class="p-2 font-bold text-sm dark:text-white">Notifications</div>
                                <div class="border-t">
                                    {{-- Loop through notifications --}}
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="flex items-center justify-between p-2 hover:bg-gray-100">
                                            <a :href="'{{ url('/') }}' + notification.data.link" class="text-sm text-gray-700 dark:text-white" x-text="notification.data.message"></a>
                                            {{-- Mark as Read Button --}}
                                            <button @click="markAsRead(notification.id)" title="Mark as read" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-200/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <div x-show="unreadCount === 0" class="p-4 text-sm text-gray-500 text-center">No new notifications</div>
                                </div>
                            </div>
                        </div>

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
                                <x-dropdown-link :href="route('admin.profile.edit')">
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
