<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Products</a>
                <span class="text-gray-400 mx-2">/</span>
                {{ $product['name'] }}
            </h2>
            <div class="flex items-center space-x-2">
                @if($product['permissions']['update'])
                    <a href="{{ route('products.edit', $product['id']) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Edit</a>
                @endif
                @if($product['permissions']['delete'])
                    <form action="{{ route('products.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf @method('DELETE')
                        <x-danger-button>Delete</x-danger-button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- This Alpine.js component will control which tab is visible --}}
    <div class="py-12" x-data="{ tab: '{{ request('tab', 'overview') }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tab Navigation --}}
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">

                            {{-- OVERVIEW TAB LINK --}}
                            <a href="{{ route('products.show', ['product' => $product['id'], 'tab' => 'overview']) }}"
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                               :class="{ 'border-indigo-500 text-indigo-600': tab === 'overview', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'overview' }">
                                Overview
                            </a>

                            {{-- PURCHASES TAB LINK --}}
                            <a href="{{ route('products.show', ['product' => $product['id'], 'tab' => 'purchases']) }}"
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                               :class="{ 'border-indigo-500 text-indigo-600': tab === 'purchases', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'purchases' }">
                                Purchases
                            </a>

                        </nav>
                    </div>

                    {{-- Overview Tab Content --}}
                    <div x-show="tab === 'overview'" class="mt-6 space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Left Column: Image and Details --}}
                            <div class="lg:col-span-1 space-y-4">
                                <img src="{{ $product['imageUrl'] ? : 'https://via.placeholder.com/400' }}" alt="{{ $product['name'] }}" class="rounded-lg shadow-md w-full object-cover">
                                <p class="text-3xl font-bold">${{ number_format($product['price'], 2) }}</p>
                                <p class="text-sm text-gray-600">{{ $product['description'] }}</p>
                                <p class="text-sm">Product SKU: <span class="font-medium text-gray-800">{{ $product['sku'] }}</span></p>
                                <div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product['status'] }}
                                    </span>
                                </div>
                            </div>
                            {{-- Right Column: Stats and Info --}}
                            <div class="lg:col-span-2 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-gray-50 p-6 rounded-lg"><h3 class="text-sm font-medium text-gray-500">Current Stock</h3><p class="mt-1 text-3xl font-semibold text-gray-900">{{ $product['overviewStats']['currentStock'] }}</p></div>
                                    <div class="bg-gray-50 p-6 rounded-lg"><h3 class="text-sm font-medium text-gray-500">Reorder Point</h3><p class="mt-1 text-3xl font-semibold text-gray-900">{{ $product['overviewStats']['reorderPoint'] }}</p></div>
                                    <div class="bg-gray-50 p-6 rounded-lg"><h3 class="text-sm font-medium text-gray-500">Monthly Sales</h3><p class="mt-1 text-3xl font-semibold text-gray-900">{{ $product['overviewStats']['monthlySales'] }}</p></div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium">Product Details</h3>
                                    <dl class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                        <div class="grid grid-cols-3 gap-1"><dt class="col-span-1 text-gray-500">Product Name</dt><dd class="col-span-2 font-medium">{{ $product['name'] }}</dd></div>
                                        <div class="grid grid-cols-3 gap-1"><dt class="col-span-1 text-gray-500">Category</dt><dd class="col-span-2 font-medium">{{ $product['category'] }}</dd></div>
                                        <div class="grid grid-cols-3 gap-1"><dt class="col-span-1 text-gray-500">Expiry Date</dt><dd class="col-span-2 font-medium">{{ $product['expiryDate'] ? \Carbon\Carbon::parse($product['expiryDate'])->format('d M Y') : 'N/A' }}</dd></div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Purchases Tab Content --}}
                    <div x-show="tab === 'purchases'" class="mt-6" style="display: none;">
                        <h3 class="text-lg font-medium mb-4">Purchase History</h3>

                        {{-- Filter and Sort Form --}}
                        <div class="mb-4">
                            <form action="{{ route('products.show', ['product' => $product['id']]) }}" method="GET">
                                <input type="hidden" name="tab" value="purchases">

                                <div class="flex items-center space-x-4">
                                    {{-- Sort By --}}
                                    <div>
                                        <select name="sort_by" id="sort_by" class="block w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="newest" @if(request('sort_by', 'newest') == 'newest') selected @endif>Newest First</option>
                                            <option value="oldest" @if(request('sort_by') == 'oldest') selected @endif>Oldest First</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-primary-button>Apply</x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Purchase history table --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchase Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchase Date</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchases as $purchase)
                                    <tr>
                                        {{-- Display Product Image --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product['imageUrl'])
                                                <div class="h-16 w-16">
                                                    <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}" class="h-full w-full object-contain">
                                                </div>
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded-md">
                                                    <span class="text-xs text-gray-500">No img</span>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Product Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $purchase['product']['name'] }}</td>

                                        {{-- Product SKU --}}
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase['product']['sku'] }}</td>

                                        {{-- Category Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $purchase['product']['category'] }}</td>

                                        {{-- Stock Quantity from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase['stock_quantity'] }}</td>

                                        {{-- Purchase Price from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($purchase['purchase_price'], 2) }}</td>

                                        {{-- Purchase Date from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($purchase['stock_purchase_date'])->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No purchase history found for this product name.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $purchases->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
