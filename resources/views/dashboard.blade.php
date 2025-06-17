<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Check if dashboard data exists before trying to display it --}}
            @if(!empty($dashboardData))
                <div class="space-y-6">
                    {{-- Overview Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Total Products</h3><p class="mt-1 text-3xl font-semibold">{{ $dashboardData['overview']['totalProducts'] }}</p></div>
                        <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">In Stock</h3><p class="mt-1 text-3xl font-semibold">{{ $dashboardData['overview']['inStock'] }}</p></div>
                        <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Low Stock</h3><p class="mt-1 text-3xl font-semibold">{{ $dashboardData['overview']['lowStock'] }}</p></div>
                        <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Out of Stock</h3><p class="mt-1 text-3xl font-semibold">{{ $dashboardData['overview']['outOfStock'] }}</p></div>
                    </div>

                    {{-- Charts Section --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900">Inventory Value Trend</h3>
                            <div class="mt-4" style="height: 250px;"><canvas id="inventoryValueChart"></canvas></div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
                            <div class="mt-4" style="height: 250px;"><canvas id="orderStatusChart"></canvas></div>
                        </div>
                    </div>

                    {{-- Recent Inventory Activity Table --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Inventory Activity</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50"><tr><th class="px-6 py-3 ...">Product</th><th class="px-6 py-3 ...">Update</th><th class="px-6 py-3 ...">User</th></tr></thead>
                                <tbody>
                                @forelse($dashboardData['recentActivity'] as $log)
                                    <tr>
                                        <td class="px-6 py-4">{{ $log['subject_name'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log['details'] }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log['user_name'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No recent activity.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
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
                // Chart for Inventory Value Trend
                const inventoryCtx = document.getElementById('inventoryValueChart');
                if(inventoryCtx) {
                    new Chart(inventoryCtx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($dashboardData['inventoryValue']['labels']) !!},
                            datasets: [
                                { label: 'Stock in Value', data: {!! json_encode($dashboardData['inventoryValue']['stockIn']) !!}, backgroundColor: 'rgba(59, 130, 246, 1)' },
                                { label: 'Stock out Value', data: {!! json_encode($dashboardData['inventoryValue']['stockOut']) !!}, backgroundColor: 'rgba(34, 197, 94, 1)' }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true },
                                      x: { grid: { display: false } } } }
                    });
                }

                // Chart for Order Status
                const orderStatusCtx = document.getElementById('orderStatusChart');
                if(orderStatusCtx) {
                    // This is the data for the chart slices
                    const orderStatusData = {!! json_encode($dashboardData['orderStatus']['counts']) !!};
                    // This is the total number we will display in the middle
                    const totalOrders = {{ $dashboardData['orderStatus']['total'] ?? 0 }};

                    // This custom plugin draws text in the middle of the doughnut
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
                                ctx.font = '24px Figtree, sans-serif';
                                ctx.fillStyle = '#111827';
                                ctx.fillText(totalOrders, centerX, centerY - 8);

                                ctx.font = '12px Figtree, sans-serif';
                                ctx.fillStyle = '#6B7280';
                                ctx.fillText('Total Orders', centerX, centerY + 12);
                                ctx.restore();
                            }
                        }
                    };

                    new Chart(orderStatusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Paid', 'Canceled'],
                            datasets: [{
                                data: [orderStatusData['Paid'] ?? 0, orderStatusData['Canceled'] ?? 0],
                                backgroundColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)'],
                                borderWidth: 0,
                                hoverOffset: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '80%', // Make the hole bigger for the text
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                },
                                tooltip: {enabled: true}
                            }
                        },
                        plugins: [centerTextPlugin] // Register our custom plugin
                    });
                }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
