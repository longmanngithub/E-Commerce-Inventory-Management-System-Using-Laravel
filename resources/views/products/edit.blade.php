<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- This line gets the most recent stock record for easy access below --}}
                    @php $latestStock = $product->stocks()->latest('stock_purchase_date')->first(); @endphp

                    <form method="POST" action="{{ route('products.update', $product->product_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <x-input-label for="product_name" :value="__('Product Name')" />
                                <x-text-input id="product_name" ... :value="old('product_name', $product->product_name)" disabled class="block mt-1 w-full bg-gray-100" />
                            </div>
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select name="category_id" id="category_id" disabled class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}" @selected(old('category_id', $product->category_id) == $category->category_id)>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="product_SKU" :value="__('Product SKU')" />
                                <x-text-input id="product_SKU" name="product_SKU" type="text" class="block mt-1 w-full" :value="old('product_SKU', $product->product_SKU)" disabled />
                            </div>

                            {{-- NEW: Display Total Stock (Read-Only) --}}
                            <div>
                                <x-input-label for="total_stock" :value="__('Total Stock Quantity (Calculated)')" />
                                <x-text-input id="total_stock" type="number" class="block mt-1 w-full bg-gray-100" value="{{ $product->stocks->sum('stock_quantity') }}" disabled />
                            </div>

                            {{-- Purchase price --}}
                            <div>
                                <x-input-label for="purchase_price" :value="__('Purchase Price (Cost per item)')" />
                                <x-text-input id="purchase_price" ... :value="old('purchase_price', optional($latestStock)->purchase_price)" disabled class="block mt-1 w-full bg-gray-100" />
                            </div>


                            {{-- Price --}}
                            <div>
                                <x-input-label for="product_price" :value="__('Price')" />
                                <x-text-input id="product_price" name="product_price" type="number" step="0.01" class="block mt-1 w-full" :value="old('product_price', $product->product_price)" required />
                            </div>

                            {{-- EDITABLE: Date of MOST RECENT Purchase --}}
                            <div>
                                <x-input-label for="purchase_date" :value="__('Date of Last Purchase')" />
                                <x-text-input id="purchase_date" ... :value="old('purchase_date', optional($latestStock)->stock_purchase_date)" disabled class="block mt-1 w-full bg-gray-100" />
                            </div>

                            {{-- EDITABLE: Quantity of MOST RECENT Purchase --}}
                            <div>
                                <x-input-label for="stock_quantity" :value="__('Amount from Last Purchase')" />
                                <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="block mt-1 w-full" :value="old('stock_quantity', optional($latestStock)->stock_quantity)" />
                            </div>

                            {{-- Expiry Date --}}
                            <div>
                                <x-input-label for="product_expiry_date" :value="__('Expiry Date')" />
                                <x-text-input id="product_expiry_date" name="product_expiry_date" type="date" class="block mt-1 w-full bg-gray-100" :value="old('product_expiry_date', $product->product_expiry_date)" readonly />
                            </div>

                            {{-- Product desc --}}
                            <div class="md:col-span-2">
                                <x-input-label for="product_desc" :value="__('Description')" />
                                <textarea id="product_desc" name="product_desc" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('product_desc', $product->product_desc) }}</textarea>
                                <x-input-error :messages="$errors->get('product_desc')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
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
