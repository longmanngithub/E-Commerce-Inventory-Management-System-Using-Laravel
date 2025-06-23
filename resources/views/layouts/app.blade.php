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

                            {{-- Dropdown Menu --}}
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg dark:bg-gray-700 z-50">
                                <div class="p-2 font-bold text-sm dark:text-white">Notifications</div>
                                <div class="border-t">
                                    {{-- Loop through notifications --}}
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="flex items-center justify-between p-2 hover:bg-gray-100">
                                            <a href="'{{ url('/') }}' + notification.data.link" class="text-sm text-gray-700 dark:text-white" x-text="notification.data.message"></a>
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
                                <button class="inline-flex items-center px-3 py-1 border text-xs leading-4 font-medium rounded-md text-gray-500 dark:text-white bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 ms-4">

                                    {{-- Profile Picture --}}
                                    <div class="flex-shrink-0 me-3">
                                        <img class="h-8 w-8 rounded-md object-cover" src="{{ Auth::user()->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ Auth::user()->name }}">
                                    </div>

                                    {{-- Name and Role --}}
                                    <div class="text-left">
                                        <div class="font-medium text-sm text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
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
