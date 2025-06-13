@php
    /**
     * @var float $totalProfit
     * @var float $totalRevenue
     * @var float $sales
     * @var float $netPurchaseValue
     * @var float $netSalesValue
     * @var float $yoyProfit
     * @var \Illuminate\Support\Collection $bestSellingCategories
     * @var \Illuminate\Support\Collection $chartLabels
     * @var \Illuminate\Support\Collection $chartRevenue
     * @var \Illuminate\Support\Collection $chartProfit
     * @var \Illuminate\Database\Eloquent\Collection $bestSellingProducts
     */
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analytics Report') }}
            </h2>
            {{-- Export button --}}
            <a href="{{ route('analytics.export') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Export as CSV
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Overview Cards --}}
            @php
                $overviewStats = [
                    ['label' => 'Total Profit', 'value' => $totalProfit],
                    ['label' => 'Revenue', 'value' => $totalRevenue],
                    ['label' => 'Sales', 'value' => $sales],
                    ['label' => 'Net purchase value', 'value' => $netPurchaseValue],
                    ['label' => 'Net sales value', 'value' => $netSalesValue],
                    ['label' => 'YoY Profit', 'value' => $yoyProfit],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($overviewStats as $stat)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            ${{ number_format($stat['value'], 2) }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- 2. Profit & Revenue Chart --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Profit & Revenue</h3>
                        <form action="{{ route('analytics.index') }}" method="GET">
                            <select name="time_range" id="time_range" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                <option value="6m" @if(request('time_range', '6m') == '6m') selected @endif>Last 6 Months</option>
                                <option value="30d" @if(request('time_range') == '30d') selected @endif>Last 30 Days</option>
                                <option value="1y" @if(request('time_range') == '1y') selected @endif>Last Year</option>
                            </select>
                        </form>
                    </div>
                    <div class="mt-4">
                        {{-- This canvas element is where the chart will be drawn --}}
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- 3. Best Selling Category --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900">Best Selling Category</h3>
                    <div class="mt-4 flow-root">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Turn Over</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Increase By</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bestSellingCategories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->category_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($category->turnover, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        {{-- Placeholder for "Increased By" percentage --}}
                                        +{{ rand(1, 5) }}.{{ rand(0,9) }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No sales data found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 4. Best Selling Products Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Best Selling Products</h3>

                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            See All
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        {{-- New headers to match your design --}}
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Turn Over</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Increased By</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bestSellingProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $product->product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->product_image)
                                        <div class="h-16 w-16">
                                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="h-full w-full object-contain">
                                        </div>
                                    @else
                                        <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded-md">
                                            <span class="text-xs text-gray-500">No img</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->product_SKU }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($product->category)->category_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stocks->sum('stock_quantity') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product->turnover, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{-- Placeholder logic for color --}} text-green-600">
                                    {{-- Placeholder for "Increased By" percentage --}}
                                    +{{ rand(1, 5) }}.{{ rand(0,9) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No product sales data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


    {{-- This section adds the JavaScript for the Chart --}}
    @push('scripts')
        {{-- First, we include the Chart.js library from a CDN for simplicity --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- Next, we initialize our chart --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('revenueChart');

                const labels = {!! json_encode($chartLabels) !!};
                const revenueData = {!! json_encode($chartRevenue) !!};
                const profitData = {!! json_encode($chartProfit) !!}; // Get the new profit data

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Revenue',
                                data: revenueData,
                                borderColor: 'rgba(79, 70, 229, 1)', // Blue
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Profit',
                                data: profitData,
                                borderColor: 'rgba(249, 115, 22, 1)', // Orange
                                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true } },
                        plugins: {
                            tooltip: {
                                enabled: true, // Use the enhanced default tooltip
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
