<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Analytics') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Overview Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Users</h3><p class="mt-1 text-3xl font-semibold">{{ $analyticsData['overview']['users'] }}</p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Companies</h3><p class="mt-1 text-3xl font-semibold">{{ $analyticsData['overview']['companies'] }}</p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Products</h3><p class="mt-1 text-3xl font-semibold">{{ $analyticsData['overview']['products'] }}</p></div>
                <div class="bg-white p-6 rounded-lg shadow-sm"><h3 class="text-sm font-medium text-gray-500">Orders</h3><p class="mt-1 text-3xl font-semibold">{{ $analyticsData['overview']['orders'] }}</p></div>
            </div>

            {{-- Active Subscriptions --}}
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900">Active Subscriptions</h3>
                <p class="text-sm text-gray-500">Current user distribution across plans</p>
                <div class="mt-4 grid grid-cols-2 gap-6">
                    {{-- Doughnut Chart --}}
                    <div class="col-span-1 flex items-center justify-center">
                        <div class="h-48 w-48">
                            <canvas id="subscriptionsChart"></canvas>
                        </div>
                    </div>
                    {{-- Plan Distribution List --}}
                    <div class="col-span-1 flex flex-col justify-center space-y-4">
                        @php $totalSubscriptions = $analyticsData['subscriptions']['total']; @endphp
                        @forelse($analyticsData['subscriptions']['distribution'] as $tier => $count)
                            <div>
                                <div class="flex justify-between items-baseline">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 rounded-full
                                    @if($tier === 'Basic') bg-orange-400 @elseif($tier === 'Pro') bg-blue-500 @else bg-green-500 @endif">
                                        </div>
                                        <p class="ms-2 text-sm font-medium text-gray-800">{{ $tier }} Plan</p>
                                    </div>
                                    <p class="font-semibold text-sm text-gray-800">
                                        {{ $totalSubscriptions > 0 ? round(($count / $totalSubscriptions) * 100) : 0 }}%
                                    </p>
                                </div>
                                <p class="ms-4 text-sm text-gray-500">{{ $count }} companies</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No active subscriptions.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            {{-- Best Performing Companies --}}
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Best Performing Companies</h3>
                    {{-- This button now correctly links to the owner dashboard --}}
                    <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        See All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-gray-100">
                        <tr>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Company Name</th>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($analyticsData['bestPerformingCompanies'] as $company)
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-sm font-medium text-gray-800">{{ $company['name'] }}</td>
                                <td class="py-3 text-sm text-gray-500">{{ $company['users'] }}</td>
                                <td class="py-3 text-sm text-gray-500">{{ $company['products'] }}</td>
                                <td class="py-3 text-sm text-gray-500">{{ $company['orders'] }}</td>
                                <td class="py-3 text-sm font-semibold {{ $company['revenueChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}%
                                </td>
                                <td class="py-3 text-sm font-semibold {{ $company['revenueChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $company['revenueChange'] >= 0 ? '+' : '' }}{{ number_format($company['revenueChange'], 1) }}%
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-3 text-sm text-center text-gray-500">Not enough data to determine best performing companies.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
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
                    const backgroundColors = labels.map(label => colorMap[label] || '#6B7280'); // Use gray as a fallback

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
                                ctx.font = '36px Figtree, sans-serif';
                                ctx.fillStyle = '#111827';
                                ctx.fillText(totalCompanies, centerX, centerY - 10);

                                // Subtitle
                                ctx.font = '12px Figtree, sans-serif';
                                ctx.fillStyle = '#6B7280';
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
                                tooltip: { enabled: false }
                            }
                        },
                        plugins: [centerTextPlugin]
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
