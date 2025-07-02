<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Products') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all product details</h4>
        </div>
    </x-slot>

    {{-- This Alpine.js component will control which tab is visible --}}
    <div class="py-12 px-4 lg:px-12 h-full" x-data="{
        tab: '{{ request('tab', 'overview') }}',
        showFilterModal: false,
        showDeleteModal: false,
        sortBy: '{{ request('sort_by', 'newest') }}'
    }">
        <div class="max-w-full mx-auto h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between">

                        <div class="flex">
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 me-6"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24">
                                    <path fill="currentColor" fill-rule="evenodd" d="m3.343 12l7.071 7.071L9 20.485l-7.778-7.778a1 1 0 0 1 0-1.414L9 3.515l1.414 1.414z" />
                                </svg></a>
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white flex">
                                {{ $product['name'] }}
                            </h2>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center space-x-2">
                            @if($product['permissions']['update'])
                                <a href="{{ route('products.edit', $product['id']) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                                    <svg class="w-4 h-4 md:me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <p class="hidden md:block">Edit</p></a>
                            @endif
                            @if($product['permissions']['delete'])
                                {{-- Hidden Form for Delete Action --}}
                                <form id="deleteProductForm" action="{{ route('products.destroy', $product['id']) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>

                                {{-- Delete Button (triggers modal instead of browser confirm) --}}
                                <button @click="showDeleteModal = true" type="button" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-sm shadow-sm hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                    <svg class="w-4 h-4 md:me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <p class="hidden md:block">Delete</p>
                                </button>
                            @endif
                        </div>

                    </div>

                    {{-- Tab Navigation --}}
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">

                            {{-- OVERVIEW TAB LINK --}}
                            <a href="{{ route('products.show', ['product' => $product['id'], 'tab' => 'overview']) }}"
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                               :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400': tab === 'overview', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': tab !== 'overview' }">
                                Overview
                            </a>

                            {{-- PURCHASES TAB LINK --}}
                            <a href="{{ route('products.show', ['product' => $product['id'], 'tab' => 'purchases']) }}"
                               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                               :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400': tab === 'purchases', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600': tab !== 'purchases' }">
                                Purchases
                            </a>

                        </nav>
                    </div>

                    {{-- Overview Tab Content --}}
                    <div x-show="tab === 'overview'" class="mt-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 md:px-6 pb-6">
                            {{-- Left Column: Image and Basic Info (1/3) --}}
                            <div class="lg:col-span-1 space-y-6">
                                {{-- Product Image --}}
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 flex items-center justify-center">
                                    <img src="{{ $product['imageUrl'] ? : 'https://via.placeholder.com/300' }}"
                                         alt="{{ $product['name'] }}"
                                         class="max-w-full max-h-64 object-contain">
                                </div>

                                {{-- Price --}}
                                <div>
                                    <div class="text-4xl font-bold text-gray-900 dark:text-white">${{ number_format($product['price'], 2) }}</div>
                                </div>

                                {{-- Description --}}
                                <div class="text-gray-600 dark:text-gray-300">
                                    {{ $product['description'] }}
                                </div>

                                {{-- SKU and Status --}}
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Product SKU: <span class="font-medium text-gray-900 dark:text-white">{{ $product['sku'] }}</span>
                                    </p>
                                    <div>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            ● {{ $product['status'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Stats, Details, and Variants (2/3) --}}
                            <div class="lg:col-span-2 space-y-12">
                                {{-- Metrics Cards --}}
                                <div class="flex md:grid md:grid-cols-3 gap-4 overflow-x-auto">
                                    {{-- Current Stock --}}
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md p-5 min-w-max">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Current Stock</div>
                                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product['overviewStats']['currentStock'] }}</div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-green-600 dark:text-green-400 text-sm flex items-center">
                                                ↗ 12% <span class="text-gray-500 dark:text-gray-400 ml-1">from last month</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Reorder Point --}}
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md p-5 min-w-max">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Reorder Point</div>
                                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product['overviewStats']['reorderPoint'] }}</div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-green-600 dark:text-green-400 text-sm flex items-center">
                                                ● <span class="text-gray-500 dark:text-gray-400 ml-1">Above threshold</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Monthly Sales --}}
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md p-5 min-w-max">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Monthly Sales</div>
                                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product['overviewStats']['monthlySales'] }}</div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-red-600 dark:text-red-400 text-sm flex items-center">
                                                ↘ 5% <span class="text-gray-500 dark:text-gray-400 ml-1">from last month</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Details --}}
                                <div>
                                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-8">Product Details</h2>
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-2">
                                            <dt class="text-sm text-gray-500 dark:text-gray-400">Product Name</dt>
                                            <dd class="text-sm text-right font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</dd>
                                        </div>
                                        <div class="grid grid-cols-2">
                                            <dt class="text-sm text-gray-500 dark:text-gray-400">Product Category</dt>
                                            <dd class="text-sm text-right font-medium text-gray-900 dark:text-white">{{ $product['category'] }}</dd>
                                        </div>
                                        <div class="grid grid-cols-2">
                                            <dt class="text-sm text-gray-500 dark:text-gray-400">Expiry Date</dt>
                                            <dd class="text-sm text-right font-medium text-gray-900 dark:text-white">
                                                {{ $product['expiryDate'] ? \Carbon\Carbon::parse($product['expiryDate'])->format('d/m/Y') : 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Purchases Tab Content --}}
                    <div x-show="tab === 'purchases'" class="mt-6" style="display: none;">
                        {{-- Header with Filter Button --}}
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Purchase History</h3>
                            <button @click="showFilterModal = true" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5" d="M21.25 12H8.895m-4.361 0H2.75m18.5 6.607h-5.748m-4.361 0H2.75m18.5-13.214h-3.105m-4.361 0H2.75m13.214 2.18a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm-9.25 6.607a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm6.607 6.608a2.18 2.18 0 1 0 0-4.361a2.18 2.18 0 0 0 0 4.36Z" />
                                </svg>
                                Filter & Sort
                            </button>
                        </div>

                        {{-- Purchase history table --}}
                        <div class="overflow-x-auto">
                            {{-- Desktop Table (hidden on mobile) --}}
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-tl-lg">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Purchase Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase rounded-tr-lg">Purchase Date</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($purchases as $purchase)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                                        {{-- Product Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ $purchase['product']['name'] }}</td>

                                        {{-- Display Product Image --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product['imageUrl'])
                                                <div class="h-16 w-16">
                                                    <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}" class="h-full w-full object-contain">
                                                </div>
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-md">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">No img</span>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Product SKU --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $purchase['product']['sku'] }}</td>

                                        {{-- Category Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $purchase['product']['category'] }}</td>

                                        {{-- Stock Quantity from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $purchase['stock_quantity'] }}</td>

                                        {{-- Purchase Price from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">${{ number_format($purchase['purchase_price'], 2) }}</td>

                                        {{-- Purchase Date from this specific purchase --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($purchase['stock_purchase_date'])->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No purchase history found for this product name.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            {{-- Mobile Card Layout (visible on mobile only) --}}
                            <div class="md:hidden space-y-4">
                                @forelse($purchases as $purchase)
                                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                        <div class="flex items-start space-x-4">
                                            {{-- Product Image --}}
                                            <div class="flex-shrink-0">
                                                @if($product['imageUrl'])
                                                    <div class="h-16 w-16 rounded-lg overflow-hidden">
                                                        <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}" class="h-full w-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="h-16 w-16 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-lg">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">No img</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Product Details --}}
                                            <div class="flex-1 min-w-0">
                                                {{-- Product Name --}}
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $purchase['product']['name'] }}</h3>

                                                {{-- SKU --}}
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">SKU: {{ $purchase['product']['sku'] }}</p>

                                                {{-- Category with Tag --}}
                                                <div class="flex items-center mb-3">
                                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $purchase['product']['category'] }}</span>
                                                </div>
                                            </div>

                                            {{-- Price --}}
                                            <div class="flex-shrink-0 text-right">
                                                <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                                                    USD {{ number_format($purchase['purchase_price'], 2) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Bottom Section --}}
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 items-center">
                                            <div class="text-right flex justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Quantity: {{ $purchase['stock_quantity'] }} pcs</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($purchase['stock_purchase_date'])->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-gray-500 dark:text-gray-400">No purchase history found for this product name.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4">{{ $purchases->links() }}</div>

                        {{-- Filter Modal --}}
                        <div x-show="showFilterModal"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-50 overflow-y-auto"
                             style="display: none;">
                            {{-- Background overlay --}}
                            <div class="fixed inset-0 bg-black bg-opacity-50" @click="showFilterModal = false"></div>

                            {{-- Modal content --}}
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full mx-auto p-6"
                                     @click.stop>
                                    {{-- Header --}}
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Filter & Sort Options</h3>
                                        <button @click="showFilterModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Hidden form for submission --}}
                                    <form id="filterForm" action="{{ route('products.show', ['product' => $product['id']]) }}" method="GET" style="display: none;">
                                        <input type="hidden" name="tab" value="purchases">
                                        <input type="hidden" name="sort_by" id="sort_by" x-model="sortBy">
                                    </form>

                                    {{-- Sort By Section --}}
                                    <div class="mb-8">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sort By Date</h4>
                                        <select x-model="sortBy"
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                        </select>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex space-x-3">
                                        <button @click="sortBy = 'newest'"
                                                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Reset
                                        </button>
                                        <button @click="document.getElementById('filterForm').submit()"
                                                class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Custom Delete Product Modal --}}
                    <div x-show="showDeleteModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="fixed inset-0 z-50 overflow-y-auto"
                         style="display: none;">
                        {{-- Background overlay --}}
                        <div class="fixed inset-0 bg-black bg-opacity-75" @click="showDeleteModal = false"></div>

                        {{-- Modal content --}}
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-sm w-full mx-auto overflow-hidden"
                                 @click.stop
                                 x-transition:enter="transition ease-out duration-300 delay-100"
                                 x-transition:enter-start="transform scale-95 opacity-0"
                                 x-transition:enter-end="transform scale-100 opacity-100">

                                {{-- Modal Header --}}
                                <div class="px-6 pt-6 text-center">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delete product</h2>
                                </div>

                                {{-- Modal Body --}}
                                <div class="px-6 py-4 text-center">
                                    <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">
                                        Are you sure you want to delete this product? This action cannot be undone.
                                    </p>
                                </div>

                                {{-- Modal Actions --}}
                                <div class="px-2 pb-1">
                                    <div class="space-y-1">
                                        <hr class="border-gray-200 dark:border-gray-700 -mx-2">

                                        {{-- Cancel Button --}}
                                        <button @click="showDeleteModal = false"
                                                class="w-full px-6 py-3 bg-transparent text-blue-600 dark:text-blue-400 font-semibold text-lg rounded-xl border-2 border-transparent hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 transform hover:scale-[1.02]">
                                            Cancel
                                        </button>

                                        {{-- Separator Line --}}
                                        <hr class="border-gray-200 dark:border-gray-700 -mx-2">

                                        {{-- Delete Button --}}
                                        <button @click="document.getElementById('deleteProductForm').submit()"
                                                class="w-full px-6 py-3 bg-transparent text-red-600 dark:text-red-400 font-semibold text-lg rounded-xl border-2 border-transparent hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 transform hover:scale-[1.02]">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
