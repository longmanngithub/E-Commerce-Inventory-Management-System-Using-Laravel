<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- We need 'enctype' for the file upload --}}
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="product_name" :value="__('Product Name')" />
                                <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name" :value="old('product_name')" required />
                                <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Choose category</option>

                                    {{-- This loop creates an option for each category found in the database --}}
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach

                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="product_SKU" :value="__('Product SKU')" />
                                <x-text-input id="product_SKU" class="block mt-1 w-full" type="text" name="product_SKU" :value="old('product_SKU')" required />
                                <x-input-error :messages="$errors->get('product_SKU')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stock_quantity" :value="__('Amount (Initial Stock)')" />
                                <x-text-input id="stock_quantity" class="block mt-1 w-full" type="number" name="stock_quantity" :value="old('stock_quantity')" required />
                                <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="purchase_price" :value="__('Purchase Price (Cost per item)')" />
                                <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="block mt-1 w-full" required />
                                <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="product_price" :value="__('Price')" />
                                <x-text-input id="product_price" class="block mt-1 w-full" type="number" step="0.01" name="product_price" :value="old('product_price')" required />
                                <x-input-error :messages="$errors->get('product_price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="purchase_date" :value="__('Date of Purchased')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date')" required />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            <div >
                                <x-input-label for="product_expiry_date" :value="__('Expiry Date')" />
                                <x-text-input id="product_expiry_date" class="block mt-1 w-full" type="date" name="product_expiry_date" :value="old('product_expiry_date')" />
                                <x-input-error :messages="$errors->get('product_expiry_date')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="product_desc" :value="__('Description')" />
                                <textarea id="product_desc" name="product_desc" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('product_desc') }}</textarea>
                                <x-input-error :messages="$errors->get('product_desc')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="product_image" :value="__('Product Image')" />
                                <input id="product_image" name="product_image" type="file" class="block mt-1 w-full">
                                <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Add') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
