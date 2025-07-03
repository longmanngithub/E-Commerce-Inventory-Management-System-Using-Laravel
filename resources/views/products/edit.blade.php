<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Products') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all product details</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">


                    <form method="POST" action="{{ route('products.update', $product['id']) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center p-2 md:p-6">
                            {{-- LEFT COLUMN - Product Image ONLY --}}
                            <div class="space-y-4">
                                <div class="aspect-square rounded-lg flex items-center justify-center">
                                    @if ($product['imageUrl'])
                                        <div class="h-96">
                                            <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}" class="max-w-full max-h-full object-contain rounded-lg">
                                        </div>
                                    @else
                                        <div class="text-gray-400 dark:text-gray-500 text-center">
                                            <svg class="w-20 h-20 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                            <p class="text-sm">No image uploaded</p>
                                        </div>
                                    @endif
                                </div>

                            </div>

                            {{-- RIGHT COLUMN - All Product Information --}}
                            <div class="space-y-6">

                                <div class="flex items-center">
                                    <a href="{{ $backUrl }}" class="mr-4 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </a>
                                    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                                        {{ $product['name'] }}
                                    </h2>
                                </div>

                                {{-- Product SKU and Category Info --}}
                                <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                                    <span>SKU: {{ $product['sku'] }}</span>
                                    <span class="mx-2">•</span>
                                    <span>Category: {{ $product['category'] }}</span>
                                </div>

                                {{-- Price Section --}}
                                <div class="border-b border-gray-200 dark:border-gray-600 pb-6">
                                    <div class="flex items-baseline space-x-2 mb-4">
                                        <span class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded">USD</span>
                                        <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($product['price'], 2) }}</span>
                                    </div>

                                    {{-- Editable Description --}}
                                    <div>
                                        <textarea name="product_desc" rows="3"
                                                  class="w-full text-sm text-gray-600 dark:text-gray-400 bg-transparent border border-gray-300 dark:border-gray-600 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700"
                                                  placeholder="Product description...">{{ old('product_desc', $product['description']) }}</textarea>
                                        <x-input-error :messages="$errors->get('product_desc')" class="mt-2" />
                                    </div>
                                </div>

                                {{-- Upload New Image --}}
                                <div>
                                    <x-input-label for="product_image" :value="__('Upload New Image')" class="text-gray-700 dark:text-gray-300" />
                                    <input id="product_image" name="product_image" type="file" class="block mt-1 w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md px-3 py-2 text-sm">
                                    <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                                </div>

                                {{-- Inventory Section --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Inventory</h3>

                                    <div class="flex items-center justify-between mb-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-lg px-6">
                                        <div class="flex items-center space-x-2">
                                            <svg class="text-gray-600 dark:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22c-.818 0-1.6-.33-3.163-.99C4.946 19.366 3 18.543 3 17.16V7m9 15c.818 0 1.6-.33 3.163-.99C19.054 19.366 21 18.543 21 17.16V7m-9 15V11.355M8.326 9.691L5.405 8.278C3.802 7.502 3 7.114 3 6.5s.802-1.002 2.405-1.778l2.92-1.413C10.13 2.436 11.03 2 12 2s1.871.436 3.674 1.309l2.921 1.413C20.198 5.498 21 5.886 21 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C13.87 10.564 12.97 11 12 11s-1.871-.436-3.674-1.309M6 12l2 1m9-9L7 9" color="currentColor" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">In Stock</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <button type="button" onclick="decreaseQuantity()" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">
                                                <span class="text-lg">−</span>
                                            </button>
                                            <input id="stock_quantity" name="stock_quantity" type="number"
                                                   class="w-20 text-center border-none bg-transparent font-semibold text-lg text-gray-900 dark:text-gray-100 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                   value="{{ old('stock_quantity', $product['totalStockQuantity']) }}" />
                                            <button type="button" onclick="increaseQuantity()" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">
                                                <span class="text-lg">+</span>
                                            </button>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">units</span>
                                        </div>
                                    </div>

                                    {{-- Monthly Sales and Reorder Point --}}
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div class="py-3 px-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Monthly Sales</span>
                                            </div>
                                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $product['overviewStats']['monthlySales'] }}</div>
                                        </div>

                                        <div class="py-3 px-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Reorder Point</span>
                                            </div>
                                            <input id="reorder_point" name="reorder_point" type="number"
                                                   class="text-2xl font-bold text-orange-600 dark:text-orange-400 border-none bg-transparent p-0 w-full [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                   value="{{ old('reorder_point', $product['reorderPoint']) }}" />
                                        </div>
                                    </div>
                                </div>

                                {{-- Product Details Section --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Product Details</h3>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Product Name</span>
                                            <input name="product_name"
                                                   class="text-right border-none bg-transparent p-0 font-medium text-gray-900 dark:text-gray-100 max-w-xs"
                                                   value="{{ old('product_name', $product['name']) }}" readonly />
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Product Category</span>
                                            <span class="text-right font-medium text-gray-900 dark:text-gray-100">
                                                {{ $product['category'] }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-400">Expiry Date</span>
                                            <span class="text-right font-medium text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($product['expiryDate'])->format('m/d/Y') }}
                                            </span>
                                            <input name="product_expiry_date" type="hidden" value="{{ old('product_expiry_date', $product['expiryDate']) }}" />
                                        </div>

                                        {{-- Hidden fields for other data --}}
                                        <input type="hidden" name="product_SKU" value="{{ $product['sku'] ?? '' }}">
                                        <input type="hidden" name="category_id" value="{{ $product['categoryId'] ?? '' }}">
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex space-x-3 pt-6">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md text-base justify-center flex items-center">
                                        {{ __('Update Inventory') }}
                                    </button>
                                    <button type="button" class="flex-1 py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 bg-white dark:bg-gray-800">
                                        <a href="{{ $backUrl }}">Cancel</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function increaseQuantity() {
            const input = document.getElementById('stock_quantity');
            const currentValue = parseInt(input.value) || 0;
            input.value = currentValue + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('stock_quantity');
            const currentValue = parseInt(input.value) || 0;
            if (currentValue > 0) {
                input.value = currentValue - 1;
            }
        }
    </script>
</x-app-layout>
