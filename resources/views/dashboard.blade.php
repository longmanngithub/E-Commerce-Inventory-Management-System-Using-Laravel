<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                {{-- Overview Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalProducts }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">In Stock</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $inStock }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Low Stock</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $lowStock }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">Out of Stock</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $outOfStock }}</p>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900">Inventory Value Trend</h3>
                        <div class="mt-4"><canvas id="inventoryValueChart"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
                        <div class="mt-4"><canvas id="orderStatusChart"></canvas></div>
                    </div>
                </div>

                {{-- Recent Inventory Activity Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Inventory Activity</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left ...">Product</th>
                                <th class="px-6 py-3 text-left ...">SKU</th>
                                <th class="px-6 py-3 text-left ...">Update</th>
                                <th class="px-6 py-3 text-left ...">Stock</th>
                                <th class="px-6 py-3 text-left ...">Status</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentActivity as $log)
                                {{-- Ensure the log subject is a Product before trying to display it --}}
                                @if($log->subject instanceof \App\Models\Product)
                                    @php $product = $log->subject; @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->product_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->product_SKU }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $log->details }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stocks->sum('stock_quantity') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColor = match($product->stock_status) {
                                                    'In Stock' => 'bg-green-100 text-green-800',
                                                    'Low Stock' => 'bg-yellow-100 text-yellow-800',
                                                    'Out of Stock' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ $product->stock_status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent activity.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                {{-- Inventory Value Trend (Bar Chart) --}}
                const inventoryCtx = document.getElementById('inventoryValueChart');
                new Chart(inventoryCtx, {
                    type: 'bar', // Bar chart
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Stock in',
                                data: {!! json_encode($stockInData) !!},
                                backgroundColor: 'rgba(59, 130, 246, 1)', // Blue
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Stock out',
                                data: {!! json_encode($stockOutData) !!},
                                backgroundColor: 'rgba(34, 197, 94, 1)', // Green
                                borderColor: 'rgba(34, 197, 94, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true },
                            x: { grid: { display: false } }
                        }
                    }
                });

                {{-- Order Status (Doughnut Chart) --}}
                const orderStatusCtx = document.getElementById('orderStatusChart');
                if (orderStatusCtx) {
                    new Chart(orderStatusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Paid', 'Canceled'],
                            datasets: [{
                                label: 'Order Status',
                                data: [
                                    {{ $orderStatusCounts['Paid'] ?? 0 }},
                                    {{ $orderStatusCounts['Canceled'] ?? 0 }}
                                ],
                                backgroundColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 1)'],
                                hoverOffset: 4
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
