<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Analytics Report') }}</h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company performance</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">
            {{-- Check if analytics data exists before trying to display it --}}
            @if(!empty($analyticsData))
                <div class="space-y-8">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Overview Section --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Overview</h3>

                            {{-- Overview Cards - 2 rows of 3 --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Total Profit - Green Theme --}}
                                <div class="text-center bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-700/50">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-emerald-700 dark:text-emerald-300">USD {{ number_format($analyticsData['overview']['totalProfit']) }}</p>
                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 mt-1 font-medium">Total Profit</p>
                                </div>

                                {{-- Revenue - Blue Theme --}}
                                <div class="text-center bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-700/50">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-blue-700 dark:text-blue-300">USD {{ number_format($analyticsData['overview']['totalRevenue']) }}</p>
                                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-1 font-medium">Revenue</p>
                                </div>

                                {{-- Sales - Purple Theme --}}
                                <div class="text-center bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700/50">
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-purple-700 dark:text-purple-300">USD {{ number_format($analyticsData['overview']['sales']) }}</p>
                                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-1 font-medium">Sales</p>
                                </div>

                                {{-- Net Purchase Value - Orange Theme --}}
                                <div class="text-center bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-4 border border-orange-200 dark:border-orange-700/50">
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m-2.4 0L3 3H1m6 10v6a1 1 0 001 1h1m5-6v6a1 1 0 001 1h1"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-orange-700 dark:text-orange-300">USD {{ number_format($analyticsData['overview']['netPurchaseValue']) }}</p>
                                    <p class="text-sm text-orange-600 dark:text-orange-400 mt-1 font-medium">Net purchase value</p>
                                </div>

                                {{-- Net Sales Value - Teal Theme --}}
                                <div class="text-center bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 rounded-xl p-4 border border-teal-200 dark:border-teal-700/50">
                                    <div class="w-8 h-8 bg-teal-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-teal-700 dark:text-teal-300">USD {{ number_format($analyticsData['overview']['netSalesValue']) }}</p>
                                    <p class="text-sm text-teal-600 dark:text-teal-400 mt-1 font-medium">Net sales value</p>
                                </div>

                                {{-- YoY Profit - Rose Theme --}}
                                <div class="text-center bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/20 dark:to-rose-800/20 rounded-xl p-4 border border-rose-200 dark:border-rose-700/50">
                                    <div class="w-8 h-8 bg-rose-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-2xl md:text-3xl font-bold text-rose-700 dark:text-rose-300">USD {{ number_format($analyticsData['overview']['yoyProfit']) }}</p>
                                    <p class="text-sm text-rose-600 dark:text-rose-400 mt-1 font-medium">YoY Profit</p>
                                </div>

                            </div>
                        </div>

                        {{-- Best Selling Category --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Best Selling Category</h3>

                            <div class="space-y-1">
                                <div class="grid grid-cols-3 gap-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                    <span>Category</span>
                                    <span>Turn Over</span>
                                    <span>Increase By</span>
                                </div>

                                @forelse($analyticsData['bestSellingCategories'] as $category)
                                    <div class="grid grid-cols-3 gap-4 py-3 text-sm border-b border-gray-50 dark:border-gray-700/50 last:border-b-0">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $category['category_name'] }}</span>
                                        <span class="text-gray-600 dark:text-gray-400">USD {{ number_format($category['turnover']) }}</span>
                                        <span class="font-semibold {{ $category['increase_by'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $category['increase_by'] >= 0 ? '+' : '' }}{{ number_format($category['increase_by'], 1) }}%
                                        </span>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-gray-500 dark:text-gray-400">No sales data to determine best selling categories.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    {{-- Profit & Revenue Chart --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Profit & Revenue</h3>

                            {{-- Filter button --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <span>
                                            @php
                                                $timeRange = request()->input('time_range', '6m');
                                                $filterLabels = [
                                                    '30d' => 'Last 30 Days',
                                                    '6m' => 'Monthly',
                                                    '1y' => 'Last Year'
                                                ];
                                            @endphp
                                            {{ $filterLabels[$timeRange] ?? 'Monthly' }}
                                        </span>
                                    <svg class="ms-2 -me-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 dark:ring-gray-600 z-10" style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('analytics.index', ['time_range' => '30d']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->input('time_range') === '30d' ? 'bg-gray-100 dark:bg-gray-600' : '' }}">Last 30 Days</a>
                                        <a href="{{ route('analytics.index', ['time_range' => '6m']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->input('time_range', '6m') === '6m' ? 'bg-gray-100 dark:bg-gray-600' : '' }}">Monthly</a>
                                        <a href="{{ route('analytics.index', ['time_range' => '1y']) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->input('time_range') === '1y' ? 'bg-gray-100 dark:bg-gray-600' : '' }}">Last Year</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4" style="height: 320px;">
                            <canvas id="profitRevenueChart"></canvas>
                        </div>
                    </div>

                    {{-- Best Selling Product Table --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Best selling product</h3>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">See All
                                <svg class="ml-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr class="border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tl-lg">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remaining Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Turn Over</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider rounded-tr-lg">Increased By</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse($analyticsData['bestSellingProducts'] as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product['image_url'])
                                                <div class="h-16 w-16 rounded-lg overflow-hidden">
                                                    <img src="{{ $product['image_url'] ?? '...' }}" alt="{{ $product['name'] }}" class="h-full w-full object-contain">
                                                </div>
                                            @else
                                                <div class="h-10 w-10 bg-gray-100 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">No img</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $product['sku'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $product['category'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $product['remaining_quantity'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">USD {{ number_format($product['turnover']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $product['increased_by'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $product['increased_by'] >= 0 ? '+' : '' }}{{ number_format($product['increased_by'], 1) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No product sales data found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8">
                    <p class="text-center text-gray-500 dark:text-gray-400">Could not load analytics data at this time.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(!empty($analyticsData) && !empty($analyticsData['charts']['profitAndRevenue']))
                const ctx = document.getElementById('profitRevenueChart');
                if (ctx) {
                    // Function to get current theme colors
                    function getThemeColors() {
                        const isDark = document.documentElement.classList.contains('dark');
                        return {
                            textColor: isDark ? '#D1D5DB' : '#6B7280',
                            gridColor: isDark ? '#374151' : '#E5E7EB',
                            borderColor: isDark ? '#4B5563' : '#D1D5DB'
                        };
                    }

                    function createChart() {
                        const colors = getThemeColors();

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($analyticsData['charts']['profitAndRevenue']['labels']) !!},
                                datasets: [
                                    {
                                        label: 'Revenue',
                                        data: {!! json_encode($analyticsData['charts']['profitAndRevenue']['revenue']) !!},
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 2,
                                        pointRadius: 4
                                    },
                                    {
                                        label: 'Profit',
                                        data: {!! json_encode($analyticsData['charts']['profitAndRevenue']['profit']) !!},
                                        borderColor: 'rgba(249, 115, 22, 1)',
                                        backgroundColor: 'rgba(249, 115, 22, 0.15)',
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 2,
                                        pointRadius: 4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: colors.textColor,
                                            usePointStyle: true,
                                            padding: 20,
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: document.documentElement.classList.contains('dark') ? '#374151' : '#ffffff',
                                        titleColor: colors.textColor,
                                        bodyColor: colors.textColor,
                                        borderColor: colors.borderColor,
                                        borderWidth: 1
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false, // Hide vertical grid lines
                                            borderColor: colors.borderColor
                                        },
                                        ticks: {
                                            color: colors.textColor,
                                            font: {
                                                size: 11
                                            }
                                        },
                                        border: {
                                            color: colors.borderColor
                                        }
                                    },
                                    y: {
                                        grid: {
                                            color: colors.gridColor,
                                            borderColor: colors.borderColor,
                                            drawBorder: true
                                        },
                                        ticks: {
                                            color: colors.textColor,
                                            font: {
                                                size: 11
                                            },
                                            callback: function(value) {
                                                return 'USD ' + value.toLocaleString();
                                            }
                                        },
                                        border: {
                                            color: colors.borderColor
                                        }
                                    }
                                }
                            }
                        });
                    }

                    createChart();

                    // Listen for theme changes (if using theme switcher)
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                // Re-create chart with new theme colors
                                Chart.getChart(ctx)?.destroy();
                                createChart();
                            }
                        });
                    });

                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
