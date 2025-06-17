<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Analytics Report') }}</h2>
            <form action="{{ route('analytics.export') }}" method="GET">
                @csrf
                <button type="submit" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Export</button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Check if analytics data exists before trying to display it --}}
            @if(!empty($analyticsData))
                <div class="space-y-6">
                    {{-- Overview Cards --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">Total Profit</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['totalProfit'], 2) }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">Revenue</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['totalRevenue'], 2) }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">Sales</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['sales'], 2) }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">Net Purchase Value</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['netPurchaseValue'], 2) }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">Net Sales Value</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['netSalesValue'], 2) }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-sm font-medium text-gray-500">YoY Profit</h3>
                            <p class="mt-1 text-3xl font-semibold">${{ number_format($analyticsData['overview']['yoyProfit'], 2) }}</p>
                        </div>
                    </div>

                    {{-- Charts and Best Selling Categories --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">

                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Profit & Revenue</h3>

                                {{-- Filter button --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="inline-flex items-center justify-center px-4 py-2 border rounded-md text-sm font-medium">
                                        <span>{{ ucfirst(request()->input('time_range', '6m')) }}</span>
                                        <svg class="ms-2 -me-1 h-5 w-5" ...> ... </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                                        <div class="py-1">
                                            <a href="{{ route('analytics.index', ['time_range' => '30d']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 30 Days</a>
                                            <a href="{{ route('analytics.index', ['time_range' => '6m']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last 6 Months</a>
                                            <a href="{{ route('analytics.index', ['time_range' => '1y']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Last Year</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4" style="height: 300px;"><canvas id="profitRevenueChart"></canvas></div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Best Selling Category</h3>
                            <table class="min-w-full">
                                <thead>
                                <tr>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Turn Over</th>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Increase By</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                @forelse($analyticsData['bestSellingCategories'] as $category)
                                    <tr>
                                        <td class="py-3 text-sm font-medium">{{ $category['category_name'] }}</td>
                                        <td class="py-3 text-sm text-gray-700">${{ number_format($category['turnover'], 2) }}</td>
                                        <td class="py-3 text-sm font-semibold {{ $category['increase_by'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $category['increase_by'] >= 0 ? '+' : '' }}{{ number_format($category['increase_by'], 1) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-3 text-sm text-center text-gray-500">No sales data to determine best selling categories.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Best Selling Product Table --}}
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Best Selling Products</h3>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">See All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="border-b-2 border-gray-100">
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
                                <tbody class="divide-y divide-gray-100">
                                @forelse($analyticsData['bestSellingProducts'] as $product)
                                    <tr class="text-sm">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $product['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><img src="{{ $product['image_url'] ?? '...' }}" class="h-10 w-10 rounded-md object-contain"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['sku'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['category'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['remaining_quantity'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product['turnover'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $product['increased_by'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $product['increased_by'] >= 0 ? '+' : '' }}{{ number_format($product['increased_by'], 1) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No product sales data found.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-center text-gray-500">Could not load analytics data at this time.</p>
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
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($analyticsData['charts']['profitAndRevenue']['labels']) !!},
                            datasets: [
                                { label: 'Revenue',
                                    data: {!! json_encode($analyticsData['charts']['profitAndRevenue']['revenue']) !!},
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    tension: 0.4,
                                    fill: true },
                                { label: 'Profit',
                                    data: {!! json_encode($analyticsData['charts']['profitAndRevenue']['profit']) !!},
                                    borderColor: 'rgba(249, 115, 22, 1)', backgroundColor: 'rgba(249, 115, 22, 0.1)',
                                    tension: 0.4,
                                    fill: true }
                            ]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                }
                @endif
            });
        </script>
    @endpush
</x-app-layout>
