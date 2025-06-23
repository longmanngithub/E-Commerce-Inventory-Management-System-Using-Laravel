<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Company Overview') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company information</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="flex justify-between items-center mb-8">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('owner.dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        &larr; {{ $companyData['name'] }}
                    </a>
                </h2>

                {{-- Actions Header --}}
                <div>
                    {{-- Conditionally show the Reactivate button --}}
                    @if($companyData['status'] === 'Inactive')
                        <form action="{{ route('company.reactivate', $companyData['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to reactivate this company?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-base text-white hover:bg-green-500 dark:bg-green-700 dark:hover:bg-green-600">
                                Reactivate Company
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left Column - Company Logo & Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 space-y-6">
                        {{-- Company Logo --}}
                        <div class="flex justify-center">
                            @if($companyData['imageUrl'])
                                <img src="{{ $companyData['imageUrl'] }}" alt="{{ $companyData['name'] }}" class="w-32 h-32 object-contain">
                            @else
                                <div class="w-32 h-32 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-2xl">{{ substr($companyData['name'], 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Description --}}
                        <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $companyData['desc'] ?? 'No description provided.' }}
                        </div>

                        {{-- Company Info --}}
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Company ID:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['id'] }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $companyData['status'] === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 {{ $companyData['status'] === 'Active' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-1"></div>
                                    {{ $companyData['status'] }}
                                </span>
                            </div>

                            @if($companyData['subscription'])
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Subscription Plan:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['subscription']['plan'] }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 dark:text-gray-400">Subscription Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $companyData['subscription']['status'] === 'Paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        <div class="w-2 h-2 {{ $companyData['subscription']['status'] === 'Paid' ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full mr-1"></div>
                                        {{ $companyData['subscription']['status'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column - Metrics & Details --}}
                <div class="lg:col-span-3 space-y-8">

                    {{-- Key Metrics Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- All Products --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">All Products</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $companyData['analytics']['allProducts'] }}</div>
                        </div>

                        {{-- Revenue --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">Revenue</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">+ {{ number_format($companyData['analytics']['revenue'], 1) }}%</div>
                            <div class="text-{{ $companyData['analytics']['revenueChange'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $companyData['analytics']['revenueChange'] >= 0 ? 'green' : 'red' }}-400 text-sm font-medium">
                                {{ $companyData['analytics']['revenueChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['revenueChange']), 1) }}% from last month
                            </div>
                        </div>

                        {{-- Sales --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">Sales</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">+ {{ number_format($companyData['analytics']['sales'], 1) }}%</div>
                            <div class="text-{{ $companyData['analytics']['salesChange'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $companyData['analytics']['salesChange'] >= 0 ? 'green' : 'red' }}-400 text-sm font-medium">
                                {{ $companyData['analytics']['salesChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['salesChange']), 1) }}% from last month
                            </div>
                        </div>
                    </div>

                    {{-- Company Details Section --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Company Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 text-base">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Company Name</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['name'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Address</span>
                                    <span class="font-medium text-gray-900 dark:text-white text-right max-w-xs">{{ $companyData['companyDetails']['address'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Company Email</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['email'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Next Billing Cycle</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['nextBilling'] ? \Carbon\Carbon::parse($companyData['companyDetails']['nextBilling'])->format('F d, Y') : 'N/A' }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Member Since</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['memberSince'] ? \Carbon\Carbon::parse($companyData['companyDetails']['memberSince'])->format('F d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Best Selling Products Section --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Best Selling Products</h3>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product Name</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sales</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($companyData['bestSellingProducts'] as $product)
                                    <tr>
                                        <td class="py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product['product_name'] }}</td>
                                        <td class="py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product['category_name'] }}</td>
                                        <td class="py-4 text-sm font-semibold text-green-600 dark:text-green-400">+{{ number_format($product['revenue'], 1) }}%</td>
                                        <td class="py-4 text-sm font-semibold text-green-600 dark:text-green-400">+{{ number_format($product['revenue'], 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-sm text-center text-gray-500 dark:text-gray-400">No product sales data for this company.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
