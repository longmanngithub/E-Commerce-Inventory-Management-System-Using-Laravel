<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Dashboard') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company dashboard</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">
            {{-- Check if dashboard data exists before trying to display it --}}
            @if(!empty($dashboardData))
                <div class="space-y-6 h-full">
                    {{-- Overview Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#007AFF" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3zM7 16.5l-4.74-2.85M7 16.5l5-3m-5 3v5.17m5-8.17V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5zm5 3l-5-3m5 3l4.74-2.85M17 16.5v5.17" />
                                        <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3l5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0zM12 8L7.26 5.15M12 8l4.74-2.85M12 13.5V8" />
                                    </g>
                                </svg>
                                <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Total Products</h2>
                            </div>
                            <p class="mt-3 text-4xl font-semibold text-gray-900 dark:text-white">{{ $dashboardData['overview']['totalProducts'] }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#34C759" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path d="M12 22v-9m3.17-10.79a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.66 1.66 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z" />
                                        <path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13" />
                                        <path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.64 1.64 0 0 0 1.63 0z" />
                                    </g>
                                </svg>
                                <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">In Stock</h2>
                            </div>
                            <p class="mt-3 text-4xl font-semibold text-green-600 dark:text-green-400">{{ $dashboardData['overview']['inStock'] }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                                    <g class="warning-outline">
                                        <g fill="#FF9500" class="Vector">
                                            <path fill-rule="evenodd" d="M12 13.8a1 1 0 0 1-1-1v-5a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1" clip-rule="evenodd" />
                                            <path d="M10.947 15.958a1.053 1.053 0 1 1 2.106 0a1.053 1.053 0 0 1-2.106 0" />
                                            <path fill-rule="evenodd" d="m15.607 4.642l5.876 10.72c1.512 2.759-.473 6.138-3.607 6.138H6.124c-3.134 0-5.12-3.38-3.607-6.139l5.876-10.72c1.566-2.855 5.648-2.855 7.214 0Zm-1.804 1c-.782-1.429-2.824-1.429-3.606 0L4.32 16.36c-.757 1.38.236 3.069 1.803 3.069h11.752c1.567 0 2.56-1.69 1.803-3.07z" clip-rule="evenodd" />
                                        </g>
                                    </g>
                                </svg>
                                <h2 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Low Stock</h2>
                            </div>
                            <p class="mt-3 text-4xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $dashboardData['overview']['lowStock'] }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24">
                                    <path fill="#FF3B30" fill-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2S2 6.477 2 12s4.477 10 10 10m4.066-14.066a.75.75 0 0 1 0 1.06L13.06 12l3.005 3.005a.75.75 0 0 1-1.06 1.06L12 13.062l-3.005 3.005a.75.75 0 1 1-1.06-1.06L10.938 12L7.934 8.995a.75.75 0 1 1 1.06-1.06L12 10.938l3.005-3.005a.75.75 0 0 1 1.06 0" clip-rule="evenodd" />
                                </svg>
                                <h3 class="ms-3 text-lg font-medium text-gray-500 dark:text-gray-400">Out of Stock</h3>
                            </div>
                            <p class="mt-3 text-4xl font-semibold text-red-600 dark:text-red-400">{{ $dashboardData['overview']['outOfStock'] }}</p>
                        </div>
                    </div>

                    {{-- Charts Section --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Inventory Value Trend</h3>
                            <div class="mt-4" style="height: 250px;"><canvas id="inventoryValueChart"></canvas></div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Order Status</h3>
                            <div class="mt-4" style="height: 250px;"><canvas id="orderStatusChart"></canvas></div>
                        </div>
                    </div>

                    {{-- Recent Inventory Activity Table --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="p-6 text-gray-900 dark:text-white">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Inventory Activity</h3>
                                <a href="{{ route('management.logs.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
                                    View All
                                    <svg class="ml-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase rounded-tl-lg">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SKU</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Update</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase rounded-tr-lg">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($dashboardData['recentActivity'] as $log)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                <div class="flex items-center">
                                                    <div class="h-16 w-16 flex-shrink-0">
                                                        <img class="h-full w-full rounded-md object-contain" src="{{ $log['product_image_url'] ?? '...' }}" alt="">
                                                    </div>
                                                    <div class="ml-4">{{ $log['subject_name'] ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log['subject_sku'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">{{ $log['detail'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log['current_stock'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColor = match($log['stock_status']) {
                                                        'In Stock' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'Low Stock' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'Out of Stock' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                    };
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                    {{ $log['stock_status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No recent product activity.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6 text-gray-900 dark:text-white">
                        Could not load dashboard data at this time.
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(!empty($dashboardData))

                // Function to get colors based on dark mode - improved detection
                function getColors() {
                    const isDark = document.documentElement.classList.contains('dark') ||
                        window.matchMedia('(prefers-color-scheme: dark)').matches ||
                        document.body.classList.contains('dark');

                    return {
                        textColor: isDark ? '#F3F4F6' : '#374151',
                        gridColor: isDark ? '#4B5563' : '#E5E7EB',
                        backgroundColor: isDark ? '#374151' : '#FFFFFF',
                        borderColor: isDark ? '#6B7280' : '#D1D5DB'
                    };
                }

                let inventoryChart, orderChart;

                // Function to create/update inventory chart
                function createInventoryChart() {
                    const inventoryCtx = document.getElementById('inventoryValueChart');
                    if(inventoryCtx) {
                        const colors = getColors();

                        // Destroy existing chart if it exists
                        if (inventoryChart) {
                            inventoryChart.destroy();
                        }

                        inventoryChart = new Chart(inventoryCtx, {
                            type: 'bar',
                            data: {
                                labels: {!! json_encode($dashboardData['inventoryValue']['labels']) !!},
                                datasets: [
                                    {
                                        label: 'Stock in Value',
                                        data: {!! json_encode($dashboardData['inventoryValue']['stockIn']) !!},
                                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Stock out Value',
                                        data: {!! json_encode($dashboardData['inventoryValue']['stockOut']) !!},
                                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                        borderColor: 'rgba(34, 197, 94, 1)',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: colors.textColor,
                                            font: {
                                                family: 'Figtree, sans-serif'
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: colors.textColor,
                                            font: {
                                                family: 'Figtree, sans-serif'
                                            }
                                        },
                                        grid: {
                                            color: colors.gridColor
                                        },
                                        border: {
                                            color: colors.borderColor
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: colors.textColor,
                                            font: {
                                                family: 'Figtree, sans-serif'
                                            }
                                        },
                                        grid: {
                                            display: false
                                        },
                                        border: {
                                            color: colors.borderColor
                                        }
                                    }
                                }
                            }
                        });
                    }
                }

                // Function to create/update order status chart
                function createOrderChart() {
                    const orderStatusCtx = document.getElementById('orderStatusChart');
                    if(orderStatusCtx) {
                        const colors = getColors();
                        const orderStatusData = {!! json_encode($dashboardData['orderStatus']['counts']) !!};
                        const totalOrders = {{ $dashboardData['orderStatus']['total'] ?? 0 }};

                        // Destroy existing chart if it exists
                        if (orderChart) {
                            orderChart.destroy();
                        }

                        // This custom plugin draws text in the middle of the doughnut
                        const centerTextPlugin = {
                            id: 'centerText',
                            afterDraw: (chart) => {
                                if (chart.config.type === 'doughnut') {
                                    let ctx = chart.ctx;
                                    ctx.save();
                                    let centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                                    let centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                                    // Get current colors for text
                                    const currentColors = getColors();

                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.font = 'bold 24px Figtree, sans-serif';
                                    ctx.fillStyle = currentColors.textColor;
                                    ctx.fillText(totalOrders, centerX, centerY - 8);

                                    ctx.font = '12px Figtree, sans-serif';
                                    ctx.fillStyle = currentColors.textColor;
                                    ctx.fillText('Total Orders', centerX, centerY + 12);
                                    ctx.restore();
                                }
                            }
                        };

                        orderChart = new Chart(orderStatusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Paid', 'Canceled'],
                                datasets: [{
                                    data: [orderStatusData['Paid'] ?? 0, orderStatusData['Canceled'] ?? 0],
                                    backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                                    borderColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)'],
                                    borderWidth: 2,
                                    hoverOffset: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        labels: {
                                            color: colors.textColor,
                                            font: {
                                                family: 'Figtree, sans-serif'
                                            },
                                            padding: 20
                                        }
                                    },
                                    tooltip: {
                                        enabled: true,
                                        titleColor: colors.textColor,
                                        bodyColor: colors.textColor,
                                        backgroundColor: colors.backgroundColor,
                                        borderColor: colors.borderColor,
                                        borderWidth: 1,
                                        cornerRadius: 8
                                    }
                                }
                            },
                            plugins: [centerTextPlugin]
                        });
                    }
                }

                // Initial chart creation
                createInventoryChart();
                createOrderChart();

                // Listen for dark mode changes and update charts dynamically
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            // Small delay to ensure DOM has updated
                            setTimeout(() => {
                                createInventoryChart();
                                createOrderChart();
                            }, 100);
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });

                // Also listen for system theme changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    setTimeout(() => {
                        createInventoryChart();
                        createOrderChart();
                    }, 100);
                });

                @endif
            });
        </script>
    @endpush
</x-app-layout>
