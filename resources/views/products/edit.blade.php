<x-app-layout>
{{--    @php--}}
{{--        dd($product, $categories);--}}
{{--    @endphp--}}


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Get latest stock from API data --}}
                    @php $latestStock = $product['stocks'][0] ?? null; @endphp

                    <form method="POST" action="{{ route('products.update', $product['id']) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Product image --}}
                            <div class="md:col-span-2">
                                <x-input-label :value="__('Current Image')" />
                                <div class="mt-2">
                                    @if ($product['imageUrl'])
                                        <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}" class="h-40 w-auto rounded-md object-contain">
                                    @else
                                        <p class="text-sm text-gray-500">No image has been uploaded for this product.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Product image --}}
                            <div class="md:col-span-2">
                                <x-input-label for="product_image" :value="__('Upload New Image (Optional)')" />
                                <input id="product_image" name="product_image" type="file" class="block mt-1 w-full">
                                <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                            </div>

                            {{-- Product name --}}
                            <div>
                                <x-input-label for="product_name" :value="__('Product Name')" />
                                <x-text-input id="product_name" ... :value="old('product_name', $product['name'])" disabled class="block mt-1 w-full bg-gray-100" />
                            </div>

                            {{-- Category --}}
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select name="category_id" id="category_id" disabled class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}" @selected(old('category_id', $product['categoryId']) == $category['id'])>
                                            {{ $category['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Product SKU --}}
                            <div>
                                <x-input-label for="product_SKU" :value="__('Product SKU')" />
                                <x-text-input id="product_SKU" name="product_SKU" type="text" class="block mt-1 w-full" :value="old('product_SKU', $product['sku'])" readonly />
                            </div>

                            {{-- Display Total Stock --}}
                            <div>
                                <x-input-label for="stock_quantity" :value="__('Quantity from Last Purchase (Editable)')" />
                                <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="block mt-1 w-full"
                                              :value="old('stock_quantity', $product['totalStockQuantity'] ?? '')" />
                                <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                            </div>

                            {{-- EDITABLE: Reorder point --}}
                            <div>
                                <x-input-label for="reorder_point" :value="__('Reorder Point (e.g., 10)')" />
                                <x-text-input id="reorder_point" class="block mt-1 w-full" type="number" name="reorder_point" :value="old('reorder_point', $product['reorderPoint'])" required />
                                <x-input-error :messages="$errors->get('reorder_point')" class="mt-2" />
                            </div>

                            {{-- Purchase price --}}
                            <div>
                                <x-input-label for="purchase_price" :value="__('Purchase Price (Cost per item)')" />
                                <x-text-input id="purchase_price" disabled class="block mt-1 w-full bg-gray-100"
                                              :value="isset($product['latestPurchase']) ? number_format($product['latestPurchase']['price'], 2) : 'N/A'" />
                            </div>


                            {{-- Price --}}
                            <div>
                                <x-input-label for="product_price" :value="__('Price')" />
                                <x-text-input id="product_price" name="product_price" type="number" step="0.01" class="block mt-1 w-full" :value="old('product_price', $product['price'])" required />
                            </div>

                            {{-- EDITABLE: Date of MOST RECENT Purchase --}}
                            <div>
                                <x-input-label for="purchase_date" :value="__('Date of Last Purchase')" />
                                <x-text-input id="purchase_date" disabled class="block mt-1 w-full bg-gray-100"
                                              :value="$product['latestPurchase']['date'] ?? 'N/A'" />
                            </div>

                            {{-- Expiry Date --}}
                            <div>
                                <x-input-label for="product_expiry_date" :value="__('Expiry Date')" />
                                <x-text-input id="product_expiry_date" name="product_expiry_date" type="date" class="block mt-1 w-full bg-gray-100" :value="old('product_expiry_date', $product['expiryDate'])" readonly />
                            </div>

                            {{-- Product desc --}}
                            <div class="md:col-span-2">
                                <x-input-label for="product_desc" :value="__('Description')" />
                                <textarea id="product_desc" name="product_desc" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('product_desc', $product['description']) }}</textarea>
                                <x-input-error :messages="$errors->get('product_desc')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ $backUrl }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
