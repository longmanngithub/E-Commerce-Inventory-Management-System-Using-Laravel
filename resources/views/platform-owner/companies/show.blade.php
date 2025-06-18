<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('owner.dashboard') }}" class="text-blue-600 hover:text-blue-800">Companies</a>
                <span class="text-gray-400 mx-2">/</span>
                {{ $companyData['name'] }}
            </h2>

            {{-- Actions Header --}}
            <div>
                {{-- Conditionally show the Reactivate button --}}
                @if($companyData['status'] === 'Inactive')
                    <form action="{{ route('company.reactivate', $companyData['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to reactivate this company?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                            Reactivate Company
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Left Column --}}
                    <div class="lg:col-span-1 bg-white p-6 shadow-sm rounded-lg space-y-4">
                        {{-- Company Logo --}}
                        @if($companyData['imageUrl'])
                            <img src="{{ $companyData['imageUrl'] }}" alt="{{ $companyData['name'] }}" class="rounded-lg w-full h-48 object-contain bg-gray-100 p-2">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">No Logo</span>
                            </div>
                        @endif

                        {{-- Description --}}
                        <p class="text-sm text-gray-600 pt-2">
                            {{ $companyData['desc'] ?? 'No description provided.' }}
                        </p>

                        {{-- Company ID --}}
                        <p class="text-sm">Company ID:
                            <span class="font-medium text-gray-800">{{ $companyData['id'] }}</span>
                        </p>

                        {{-- Company Status --}}
                        <p class="text-sm">Company Status:
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $companyData['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $companyData['status'] }}
                    </span>
                        </p>

                        {{-- Subscription Plan --}}
                        @if($companyData['subscription'])
                            <p class="text-sm">Subscription Plan:
                                <span class="font-medium text-gray-800">{{ $companyData['subscription']['plan'] }}</span>
                            </p>
                        @endif

                        {{-- Subscription Status --}}
                        @if($companyData['subscription'])
                            <p class="text-sm">Subscription Status:
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $companyData['subscription']['status'] === 'Paid' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $companyData['subscription']['status'] }}
                        </span>
                            </p>
                        @endif

                    </div>


                    {{-- Right Column --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Stat Cards --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <h3 class="text-sm font-medium text-gray-500">All Products</h3>
                                <p class="mt-1 text-3xl font-semibold">{{ $companyData['analytics']['allProducts'] }}</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <h3 class="text-sm font-medium text-gray-500">Revenue</h3>
                                <p class="mt-1 text-3xl font-semibold">${{ number_format($companyData['analytics']['revenue'], 2) }}</p>
                                <p class="mt-1 text-xs {{ $companyData['analytics']['revenueChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $companyData['analytics']['revenueChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['revenueChange']), 1) }}% from last month
                                </p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <h3 class="text-sm font-medium text-gray-500">Sales</h3>
                                <p class="mt-1 text-3xl font-semibold">${{ number_format($companyData['analytics']['sales'], 2) }}</p>
                                <p class="mt-1 text-xs {{ $companyData['analytics']['salesChange'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $companyData['analytics']['salesChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['salesChange']), 1) }}% from last month
                                </p>
                            </div>
                        </div>

                        {{-- Company Details Section --}}
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Details</h3>
                            <dl class="space-y-4 text-sm">
                                <div class="flex justify-between"><dt class="text-gray-500">Company Name</dt><dd class="font-medium text-gray-800">{{ $companyData['companyDetails']['name'] }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd class="font-medium text-gray-800">{{ $companyData['companyDetails']['address'] }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Company Email</dt><dd class="font-medium text-gray-800">{{ $companyData['companyDetails']['email'] }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Next Billing Cycle</dt><dd class="font-medium text-gray-800">{{ $companyData['companyDetails']['nextBilling'] ? \Carbon\Carbon::parse($companyData['companyDetails']['nextBilling'])->format('F d, Y') : 'N/A' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-gray-500">Member Since</dt><dd class="font-medium text-gray-800">{{ $companyData['companyDetails']['memberSince'] ? \Carbon\Carbon::parse($companyData['companyDetails']['memberSince'])->format('F d, Y') : 'N/A' }}</dd></div>
                            </dl>
                        </div>

                        {{-- Best Selling Products Section --}}
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Best Selling Products</h3>
                            <table class="min-w-full">
                                <thead class="border-b-2 border-gray-100">
                                <tr>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                    <th class="py-2 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($companyData['bestSellingProducts'] as $product)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 text-sm font-medium text-gray-800">{{ $product['product_name'] }}</td>
                                        <td class="py-3 text-sm text-gray-500">{{ $product['category_name'] }}</td>
                                        <td class="py-3 text-sm font-semibold text-green-600">+{{ number_format($product['revenue'], 1) }}%</td>
                                        <td class="py-3 text-sm font-semibold text-green-600">+{{ number_format($product['revenue'], 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-3 text-sm text-center text-gray-500">No product sales data for this company.</td></tr>
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
