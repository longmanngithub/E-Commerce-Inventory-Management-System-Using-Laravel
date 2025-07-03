<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Analytics') }}</h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company performance</h4>
        </div>
    </x-slot>
    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="sm:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Analytics') }}</h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company performance</h4>
            </div>


            {{-- Overview Cards --}}
            <div class="flex md:grid grid-cols-1 md:grid-cols-4 gap-6 mb-7 overflow-x-auto">

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 min-w-max">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                            <path fill="#007AFF" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                        </svg>
                        <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Users</h2>
                    </div>
                    <p class="mt-3 text-4xl font-semibold text-gray-900 dark:text-white">{{ number_format($analyticsData['overview']['users']) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 min-w-max">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Companies</h2>
                    </div>
                    <p class="mt-3 text-4xl font-semibold text-gray-900 dark:text-white">{{ $analyticsData['overview']['companies'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 min-w-max">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Products</h2>
                    </div>
                    <p class="mt-3 text-4xl font-semibold text-gray-900 dark:text-white">{{ number_format($analyticsData['overview']['products']) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 min-w-max">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Orders</h2>
                    </div>
                    <p class="mt-3 text-4xl font-semibold text-gray-900 dark:text-white">{{ $analyticsData['overview']['orders'] }}</p>
                </div>

            </div>

            {{-- Active Subscriptions --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-7">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Active Subscriptions</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Current user distribution across plans</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2">
                    {{-- Doughnut Chart --}}
                    <div class="flex items-center justify-center">
                        <div class="relative h-fit w-fit">
                            <canvas id="subscriptionsChart"></canvas>
                        </div>
                    </div>

                    {{-- Plan Distribution List --}}
                    <div class="flex flex-col justify-center space-y-6 lg:pe-24">
                        @php $totalSubscriptions = $analyticsData['subscriptions']['total']; @endphp
                        @forelse($analyticsData['subscriptions']['distribution'] as $tier => $count)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-3 w-3 rounded-full
                                        @if($tier === 'Basic') bg-orange-400
                                        @elseif($tier === 'Pro') bg-blue-500
                                        @else bg-green-500 @endif">
                                    </div>
                                    <div>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $tier }} Plan</p>
                                        <p class="text-base text-gray-500 dark:text-gray-400">{{ $count }} companies</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $totalSubscriptions > 0 ? round(($count / $totalSubscriptions) * 100) : 0 }}%
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No active subscriptions.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Best Performing Companies --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-white">
                    <div class="flex justify-between items-center border-gray-100 dark:border-gray-700 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Best Performing Companies</h3>
                        <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
                            See All
                            <svg class="ml-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Desktop Table Layout --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tl-lg">Company Name</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Products</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Orders</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tr-lg">Sales</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($analyticsData['bestPerformingCompanies'] as $company)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-4 px-6 text-base font-medium text-gray-900 dark:text-white">{{ $company['name'] }}</td>
                                    <td class="py-4 px-6 text-base text-gray-500 dark:text-gray-400">{{ $company['users'] }}</td>
                                    <td class="py-4 px-6 text-base text-gray-500 dark:text-gray-400">{{ $company['products'] }}</td>
                                    <td class="py-4 px-6 text-base text-gray-500 dark:text-gray-400">{{ $company['orders'] }}</td>
                                    <td class="py-4 px-6 text-base font-semibold {{ $company['revenueChange'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}%
                                    </td>
                                    <td class="py-4 px-6 text-base font-semibold {{ $company['revenueChange'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                        Not enough data to determine best performing companies.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card Layout --}}
                    <div class="md:hidden space-y-4">
                        @forelse($analyticsData['bestPerformingCompanies'] as $company)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                {{-- Company Name and Performance Badge --}}
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $company['name'] }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $company['revenueChange'] >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}%
                        </span>
                                </div>

                                {{-- Stats Row --}}
                                <div class="grid grid-cols-3 gap-3 mb-3">
                                    {{-- Users --}}
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-2">
                                            <path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                        </svg>
                                        <span class="text-sm">{{ $company['users'] }} users</span>
                                    </div>

                                    {{-- Products --}}
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-2" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span class="text-sm">{{ number_format($company['products']) }} products</span>
                                    </div>

                                    {{-- Orders --}}
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-2" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $company['orders'] }} orders</span>
                                    </div>
                                </div>

                                {{-- Sales Performance --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-{{ $company['revenueChange'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $company['revenueChange'] >= 0 ? 'green' : 'red' }}-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" class="mr-2" fill="none" stroke="currentColor">
                                            @if($company['revenueChange'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                            @endif
                                        </svg>
                                        <span class="text-sm font-medium">{{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}% sales</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Not enough data to determine best performing companies.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('subscriptionsChart');
                if (ctx) {
                    // Get the data from the controller
                    const distributionData = {!! json_encode($analyticsData['subscriptions']['distribution']) !!};
                    const totalCompanies = {{ $analyticsData['subscriptions']['total'] ?? 0 }};

                    // Define our color map to ensure colors are always correct
                    const colorMap = {
                        'Basic': '#fb923c',     // Orange
                        'Pro': '#3b82f6',       // Blue
                        'Ultimate': '#22c55e',  // Green
                    };

                    // Prepare the final labels, data, and colors for the chart
                    const labels = Object.keys(distributionData);
                    const data = Object.values(distributionData);
                    const backgroundColors = labels.map(label => colorMap[label] || '#6B7280');

                    // Check if dark mode is enabled
                    const isDarkMode = document.documentElement.classList.contains('dark') ||
                        window.matchMedia('(prefers-color-scheme: dark)').matches;

                    // This is the custom plugin to draw text in the middle
                    const centerTextPlugin = {
                        id: 'centerText',
                        afterDraw: (chart) => {
                            if (chart.config.type === 'doughnut') {
                                let ctx = chart.ctx;
                                ctx.save();
                                let centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                                let centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';

                                // Main Number
                                ctx.font = 'bold 36px system-ui, sans-serif';
                                ctx.fillStyle = isDarkMode ? '#ffffff' : '#111827';
                                ctx.fillText(totalCompanies, centerX, centerY - 10);

                                // Subtitle
                                ctx.font = '12px system-ui, sans-serif';
                                ctx.fillStyle = isDarkMode ? '#9ca3af' : '#6B7280';
                                ctx.fillText('Total Companies', centerX, centerY + 15);

                                ctx.restore();
                            }
                        }
                    };

                    // Create the chart with ordered data
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: backgroundColors,
                                hoverOffset: 4,
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '75%',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    enabled: true,
                                    backgroundColor: isDarkMode ? '#374151' : '#ffffff',
                                    titleColor: isDarkMode ? '#ffffff' : '#111827',
                                    bodyColor: isDarkMode ? '#d1d5db' : '#6b7280',
                                    borderColor: isDarkMode ? '#4b5563' : '#e5e7eb',
                                    borderWidth: 1
                                }
                            }
                        },
                        plugins: [centerTextPlugin]
                    });

                    // Update chart colors when theme changes
                    window.addEventListener('storage', function(e) {
                        if (e.key === 'theme') {
                            location.reload();
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
