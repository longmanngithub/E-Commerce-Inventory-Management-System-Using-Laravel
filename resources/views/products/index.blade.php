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

            <div class="sm:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Products') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all product details</h4>
            </div>


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Search Bar and Filter/Delete Actions --}}
                    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        {{-- Search Bar --}}
                        <div class="flex-1 max-w-md">
                            <form action="{{ route('products.index') }}" method="GET" class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search Product"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                {{-- Preserve other filters --}}
                                @foreach(request()->except(['search', 'page']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $item)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                            </form>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3">
                            {{-- Bulk Delete Button --}}
                            @can('bulk-delete-products')
                                <button type="button" id="bulk-delete-btn" disabled
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            @endcan

                            {{-- Create Product Button --}}
                            @can('create-product')
                                <button type="button" id="add-product-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 16 16">
                                        <path fill="currentColor" d="M8 15c-3.86 0-7-3.14-7-7s3.14-7 7-7s7 3.14 7 7s-3.14 7-7 7M8 2C4.69 2 2 4.69 2 8s2.69 6 6 6s6-2.69 6-6s-2.69-6-6-6" />
                                        <path fill="currentColor" d="M8 11.5c-.28 0-.5-.22-.5-.5V5c0-.28.22-.5.5-.5s.5.22.5.5v6c0 .28-.22.5-.5.5" />
                                        <path fill="currentColor" d="M11 8.5H5c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h6c.28 0 .5.22.5.5s-.22.5-.5.5" />
                                    </svg>
                                    Add
                                </button>
                            @endcan

                            {{-- Filter Button --}}
                            <button type="button" id="filter-btn"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5" d="M21.25 12H8.895m-4.361 0H2.75m18.5 6.607h-5.748m-4.361 0H2.75m18.5-13.214h-3.105m-4.361 0H2.75m13.214 2.18a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm-9.25 6.607a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm6.607 6.608a2.18 2.18 0 1 0 0-4.361a2.18 2.18 0 0 0 0 4.36Z" />
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>

                    {{-- Custom Filter Modal --}}
                    <div id="filter-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 transition-opacity duration-300">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
                            {{-- Modal Header --}}
                            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Filter</h2>
                                <button type="button" id="close-modal" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors duration-200">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Body --}}
                            <div class="overflow-y-auto max-h-[calc(90vh-200px)]">
                                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                                    <div class="p-6 space-y-6">
                                        {{-- Category Section --}}
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Category</h3>
                                            <div class="space-y-3">
                                                @foreach ($categories as $category)
                                                    <label class="flex items-center cursor-pointer group">
                                                        <div class="relative">
                                                            <input type="checkbox" name="categories[]" value="{{ $category['id'] }}"
                                                                   @if(in_array($category['id'], request('categories', []))) checked @endif
                                                                   class="sr-only category-checkbox">
                                                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 group-hover:border-blue-500 transition-colors duration-200 flex items-center justify-center">
                                                                <svg class="w-3 h-3 dark:text-white text-blue-600 hidden checkbox-icon " fill="" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <span class="ml-3 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors duration-200">{{ $category['name'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Availability Section --}}
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Availability</h3>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-700 dark:text-gray-300">Show in stock only</span>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="in_stock_only" value="1"
                                                           @if(request('in_stock_only')) checked @endif
                                                           class="sr-only toggle-checkbox">
                                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full toggle-bg transition-colors duration-300">
                                                        <div class="w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-300 toggle-dot translate-x-0.5 mt-0.5"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Sort By Section --}}
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sort By</h3>
                                            <div class="relative">
                                                <select name="sort_by" id="sort_by"
                                                        class="w-full appearance-none bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                    <option value="">Default</option>
                                                    <option value="name_asc" @if(request('sort_by') == 'name_asc') selected @endif>Name: A to Z</option>
                                                    <option value="name_desc" @if(request('sort_by') == 'name_desc') selected @endif>Name: Z to A</option>
                                                    <option value="price_asc" @if(request('sort_by') == 'price_asc') selected @endif>Price: Low to High</option>
                                                    <option value="price_desc" @if(request('sort_by') == 'price_desc') selected @endif>Price: High to Low</option>
                                                    <option value="stock_asc" @if(request('sort_by') == 'stock_asc') selected @endif>Stock: Low to High</option>
                                                    <option value="stock_desc" @if(request('sort_by') == 'stock_desc') selected @endif>Stock: High to Low</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Hidden Price Inputs (for future expansion) --}}
                                        <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                        <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                                        <input type="hidden" name="low_stock_only" value="{{ request('low_stock_only') }}">
                                        <input type="hidden" name="out_of_stock_only" value="{{ request('out_of_stock_only') }}">

                                        {{-- Preserve search query --}}
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    </div>
                                </form>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <div class="flex gap-3">
                                    <button type="button" id="reset-filters"
                                            class="flex-1 px-4 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-200">
                                        Reset
                                    </button>
                                    <button type="submit" form="filter-form"
                                            class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors duration-200">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Custom Delete Modal --}}
                    <div id="delete-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 transition-all duration-300 opacity-0">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-content">
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

                            {{-- Modal Footer --}}
                            <div class="px-2 pb-1">
                                <div class="space-y-1">
                                    <hr class="border-gray-200 dark:border-gray-700 -mx-2">
                                    <div class="pt-1">
                                        <button type="button" id="cancel-delete"
                                                class="w-full px-4 py-3 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold text-lg text-center transition-colors duration-200">
                                            Cancel
                                        </button>
                                    </div>
                                    <hr class="border-gray-200 dark:border-gray-700 -mx-2">
                                    <div class="pt-1">
                                        <button type="button" id="confirm-delete"
                                                class="w-full px-4 py-3 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-semibold text-lg text-center transition-colors duration-200">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Custom Bulk Delete Modal --}}
                    <div id="bulk-delete-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 transition-all duration-300 opacity-0">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="bulk-delete-modal-content">
                            {{-- Modal Header --}}
                            <div class="px-6 pt-6 text-center">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delete products</h2>
                            </div>

                            {{-- Modal Body --}}
                            <div class="px-6 py-4 text-center">
                                <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">
                                    Are you sure you want to delete <span id="selected-count" class="font-medium text-gray-900 dark:text-white">0</span> selected products? This action cannot be undone.
                                </p>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="px-2 pb-1">
                                <div class="space-y-1">
                                    <hr class="border-gray-200 dark:border-gray-700 -mx-2">
                                    <div class="pt-1">
                                        <button type="button" id="close-bulk-delete-modal"
                                                class="w-full px-4 py-3 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold text-lg text-center transition-colors duration-200">
                                            Cancel
                                        </button>
                                    </div>
                                    <hr class="border-gray-200 dark:border-gray-700 -mx-2">
                                    <div class="pt-1">
                                        <button type="button" id="confirm-delete"
                                                class="w-full px-4 py-3 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-semibold text-lg text-center transition-colors duration-200">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Add Product Overlay --}}
                    @include('products/create')

                    {{-- Bulk Delete Form (Hidden) --}}
                    @can('bulk-delete-products')
                        <form id="bulk-delete-form" action="{{ route('products.bulkDestroy') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endcan

                    {{-- Desktop Table Layout --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @can('bulk-delete-products')
                                    <th class="px-6 py-3 rounded-tl-lg">
                                        <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                    </th>
                                @endcan
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Product SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Availability</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 rounded-tr-lg">Action</th>
                            </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="window.location.href='{{ route('products.show', $product['id']) }}'">
                                    @can('bulk-delete-products')
                                        {{-- Checkbox --}}
                                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="product_ids[]" value="{{ $product['id'] }}"
                                                   class="product-checkbox rounded border-gray-300 text-blue-600 shadow-sm"
                                                   form="bulk-delete-form">
                                        </td>
                                    @endcan

                                    {{-- Product name --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</div>
                                    </td>

                                    {{-- Display Product Image --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product['imageUrl'])
                                            <div class="h-16 w-16">
                                                <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}"
                                                     class="h-full w-full object-contain rounded-lg">
                                            </div>
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                                    <path fill="currentColor" fill-rule="evenodd" d="m72.837 72.837l362.667 362.667l-30.17 30.17L387.66 448H64V124.34l-21.333-21.332zm204.497 289.83L170.667 256l-64.001 101.12v48.213h238.327l-56.282-56.283zM448 64v323.661L313.796 253.457l27.538-27.537l63.999 64V106.666H167.005L124.339 64zM106.666 167.005v108.872l41.741-67.131zm202.668-17.671c17.673 0 32 14.327 32 32s-14.327 32-32 32s-32-14.327-32-32s14.327-32 32-32" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Product SKU --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $product['sku'] }}</td>

                                    {{-- Display Category Name --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product['category'] }}
                                    </td>

                                    {{-- Display Stock Quantity --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $product['stockQuantity'] }} pcs
                                    </td>

                                    {{-- Product price --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        USD {{ number_format($product['price'], 2) }}
                                    </td>

                                    {{-- Status toggle --}}
                                    <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                        <a href="{{ route('products.toggleStatus', $product['id']) }}"
                                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200 {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                            @if($product['status'] === 'Active')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                </svg>
                                            @endif
                                            {{ $product['status'] }}
                                        </a>
                                    </td>

                                    {{-- Stock status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($product['stockStatus']) {
                                                'In Stock' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'Low Stock' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                'Out of Stock' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ $product['stockStatus'] }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                        <div class="flex items-center space-x-2">
                                            {{-- Edit --}}
                                            @if($product['permissions']['update'])
                                                <a href="{{ route('products.edit', $product['id']) }}?{{ http_build_query(request()->query()) }}"
                                                   class="p-2 text-white hover:text-blue-900 hover:bg-blue-50 dark:hover:bg-blue-900/20 bg-blue-600 rounded-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Delete --}}
                                            @if($product['permissions']['delete'])
                                                <button type="button" class="delete-product-btn p-2 text-white hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900/20 bg-red-600 rounded-lg"
                                                        data-product-id="{{ $product['id'] }}"
                                                        data-product-name="{{ $product['name'] }}">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->can('bulk-delete-products') ? '10' : '9' }}"
                                        class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No products found</p>
                                            <p class="text-sm">Add your first product to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card Layout --}}
                    <div class="block md:hidden space-y-4">
                        @can('bulk-delete-products')
                            {{-- Mobile Bulk Actions Header --}}
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <label class="flex items-center">
                                    <input type="checkbox" id="select-all-checkbox-mobile" class="rounded border-gray-300 text-blue-600 shadow-sm mr-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Select All</span>
                                </label>
                            </div>
                        @endcan

                        @forelse ($products as $product)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                {{-- Card Header with Image and Basic Info --}}
                                <div class="p-4 cursor-pointer" onclick="window.location.href='{{ route('products.show', $product['id']) }}'">
                                    <div class="flex items-start space-x-4">
                                        {{-- Product Image --}}
                                        <div class="flex-shrink-0">
                                            @if($product['imageUrl'])
                                                <div class="h-16 w-16">
                                                    <img src="{{ $product['imageUrl'] }}" alt="{{ $product['name'] }}"
                                                         class="h-full w-full object-contain rounded-lg">
                                                </div>
                                            @else
                                                <div class="h-20 w-20 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                                        <path fill="currentColor" fill-rule="evenodd" d="m72.837 72.837l362.667 362.667l-30.17 30.17L387.66 448H64V124.34l-21.333-21.332zm204.497 289.83L170.667 256l-64.001 101.12v48.213h238.327l-56.282-56.283zM448 64v323.661L313.796 253.457l27.538-27.537l63.999 64V106.666H167.005L124.339 64zM106.666 167.005v108.872l41.741-67.131zm202.668-17.671c17.673 0 32 14.327 32 32s-14.327 32-32 32s-32-14.327-32-32s14.327-32 32-32" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-wrap">
                                                        {{ $product['name'] }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        SKU: {{ $product['sku'] }}
                                                    </p>
                                                    <div class="flex items-center mt-2">
                                                        <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $product['category'] }}</span>
                                                    </div>
                                                </div>

                                                {{-- Stock Status Badge --}}
                                                <div class="ml-4 flex-shrink-0">
                                                    @php
                                                        $statusConfig = match($product['stockStatus']) {
                                                            'In Stock' => [
                                                                'bg' => 'bg-green-100 dark:bg-green-900/30',
                                                                'text' => 'text-green-600 dark:text-green-400',
                                                                'label' => 'In Stock: ' . $product['stockQuantity']
                                                            ],
                                                            'Low Stock' => [
                                                                'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                                                'text' => 'text-yellow-600 dark:text-yellow-400',
                                                                'label' => 'Low Stock: ' . $product['stockQuantity']
                                                            ],
                                                            'Out of Stock' => [
                                                                'bg' => 'bg-red-100 dark:bg-red-900/30',
                                                                'text' => 'text-red-600 dark:text-red-400',
                                                                'label' => 'Out of Stock'
                                                            ],
                                                            default => [
                                                                'bg' => 'bg-gray-100 dark:bg-gray-700',
                                                                'text' => 'text-gray-600 dark:text-gray-400',
                                                                'label' => $product['stockStatus']
                                                            ]
                                                        };
                                                    @endphp
                                                    <div class="px-3 py-2 rounded-full text-xs font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                        {{ $statusConfig['label'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Footer with Actions and Details --}}
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            @can('bulk-delete-products')
                                                {{-- Checkbox --}}
                                                <div onclick="event.stopPropagation()">
                                                    <input type="checkbox" name="product_ids[]" value="{{ $product['id'] }}"
                                                           class="product-checkbox rounded border-gray-300 text-blue-600 shadow-sm"
                                                           form="bulk-delete-form">
                                                </div>
                                            @endcan

                                            {{-- Price and Status --}}
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    USD {{ number_format($product['price'], 2) }}
                                                </span>

                                                {{-- Status Toggle --}}
                                                <div onclick="event.stopPropagation()">
                                                    <a href="{{ route('products.toggleStatus', $product['id']) }}"
                                                       class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200 {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                                        @if($product['status'] === 'Active')
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                            </svg>
                                                        @endif
                                                        {{ $product['status'] }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex items-center space-x-2" onclick="event.stopPropagation()">
                                            {{-- Edit --}}
                                            @if($product['permissions']['update'])
                                                <a href="{{ route('products.edit', $product['id']) }}?{{ http_build_query(request()->query()) }}"
                                                   class="p-2 text-white hover:text-blue-900 hover:bg-blue-50 dark:hover:bg-blue-900/20 bg-blue-600 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- Delete --}}
                                            @if($product['permissions']['delete'])
                                                <button type="button" class="delete-product-btn p-2 text-white hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900/20 bg-red-600 rounded-lg transition-colors"
                                                        data-product-id="{{ $product['id'] }}"
                                                        data-product-name="{{ $product['name'] }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No products found</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Add your first product to get started.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        <style>
            /* Custom checkbox styles */
            .category-checkbox:checked + div {
                @apply bg-blue-600 border-blue-600;
            }
            .category-checkbox:checked + div .checkbox-icon {
                @apply block;
            }

            /* Custom toggle styles */
            .toggle-checkbox:checked ~ .toggle-bg {
                @apply bg-green-500;
            }
            .toggle-checkbox:checked ~ .toggle-bg .toggle-dot {
                @apply translate-x-5;
            }

            /* Modal animation classes */
            .modal-enter {
                opacity: 1;
            }
            .modal-enter .modal-content-enter {
                transform: scale(1);
                opacity: 1;
            }

            /* Delete Modal Animation */
            .delete-modal-show {
                opacity: 1;
            }
            .delete-modal-show #delete-modal-content,
            .delete-modal-show #bulk-delete-modal-content {
                transform: scale(1);
                opacity: 1;
            }

            /* Add Product Overlay Animation */
            .overlay-open {
                transform: translateX(0) !important;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Modal elements
                const filterBtn = document.getElementById('filter-btn');
                const filterModal = document.getElementById('filter-modal');
                const modalContent = document.getElementById('modal-content');
                const closeModal = document.getElementById('close-modal');
                const resetFilters = document.getElementById('reset-filters');

                // Delete Modal elements
                const deleteModal = document.getElementById('delete-modal');
                const deleteModalContent = document.getElementById('delete-modal-content');
                const closeDeleteModal = document.getElementById('close-delete-modal');
                const cancelDelete = document.getElementById('cancel-delete');
                const confirmDelete = document.getElementById('confirm-delete');
                let currentDeleteForm = null;

                // Bulk Delete Modal elements
                const bulkDeleteModal = document.getElementById('bulk-delete-modal');
                const bulkDeleteModalContent = document.getElementById('bulk-delete-modal-content');
                const closeBulkDeleteModal = document.getElementById('close-bulk-delete-modal');
                const cancelBulkDelete = document.getElementById('cancel-bulk-delete');
                const confirmBulkDelete = document.getElementById('confirm-bulk-delete');
                const selectedCount = document.getElementById('selected-count');

                // Add Product Overlay elements
                const addProductBtn = document.getElementById('add-product-btn');
                const addProductOverlay = document.getElementById('add-product-overlay');
                const addProductPanel = document.getElementById('add-product-panel');
                const closeAddProduct = document.getElementById('close-add-product');
                const cancelAddProduct = document.getElementById('cancel-add-product');

                // Image upload elements
                const uploadBtn = document.getElementById('upload-btn');
                const productImageInput = document.getElementById('product_image');
                const imagePreview = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');
                const removeImageBtn = document.getElementById('remove-image');

                // Bulk delete elements
                const selectAll = document.getElementById('select-all-checkbox');
                const checkboxes = document.querySelectorAll('.product-checkbox');
                const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

                // Search functionality
                const searchInput = document.querySelector('input[name="search"]');

                // Delete Modal Functions
                function openDeleteModal() {
                    deleteModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    setTimeout(() => {
                        deleteModal.classList.add('delete-modal-show');
                    }, 10);
                }

                function closeDeleteModalFunction() {
                    deleteModal.classList.remove('delete-modal-show');

                    setTimeout(() => {
                        deleteModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        currentDeleteForm = null;
                    }, 300);
                }

                // Bulk Delete Modal Functions
                function openBulkDeleteModal(count) {
                    selectedCount.textContent = count;
                    bulkDeleteModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    setTimeout(() => {
                        bulkDeleteModal.classList.add('delete-modal-show');
                    }, 10);
                }

                function closeBulkDeleteModalFunction() {
                    bulkDeleteModal.classList.remove('delete-modal-show');

                    setTimeout(() => {
                        bulkDeleteModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }, 300);
                }

                // Delete Product Button Event Listeners
                document.querySelectorAll('.delete-product-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');

                        // Create a form dynamically for deletion
                        currentDeleteForm = document.createElement('form');
                        currentDeleteForm.action = `/products/${productId}`;
                        currentDeleteForm.method = 'POST';
                        currentDeleteForm.style.display = 'none';

                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // Add DELETE method
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        currentDeleteForm.appendChild(csrfToken);
                        currentDeleteForm.appendChild(methodField);
                        document.body.appendChild(currentDeleteForm);

                        openDeleteModal();
                    });
                });

                // Delete Modal Event Listeners
                if (closeDeleteModal) closeDeleteModal.addEventListener('click', closeDeleteModalFunction);
                if (cancelDelete) cancelDelete.addEventListener('click', closeDeleteModalFunction);

                if (confirmDelete) {
                    confirmDelete.addEventListener('click', function() {
                        if (currentDeleteForm) {
                            currentDeleteForm.submit();
                        }
                        closeDeleteModalFunction();
                    });
                }

                // Close delete modal when clicking outside
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === deleteModal) {
                        closeDeleteModalFunction();
                    }
                });

                // Bulk Delete Modal Event Listeners
                if (closeBulkDeleteModal) closeBulkDeleteModal.addEventListener('click', closeBulkDeleteModalFunction);
                if (cancelBulkDelete) cancelBulkDelete.addEventListener('click', closeBulkDeleteModalFunction);

                if (confirmBulkDelete) {
                    confirmBulkDelete.addEventListener('click', function() {
                        document.getElementById('bulk-delete-form').submit();
                        closeBulkDeleteModalFunction();
                    });
                }

                // Close bulk delete modal when clicking outside
                bulkDeleteModal.addEventListener('click', function(e) {
                    if (e.target === bulkDeleteModal) {
                        closeBulkDeleteModalFunction();
                    }
                });

                // Add Product Overlay functionality
                if (addProductBtn && addProductOverlay) {
                    const openAddProductOverlay = function() {
                        addProductOverlay.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        setTimeout(() => {
                            addProductPanel.classList.add('overlay-open');
                        }, 10);
                    };

                    const closeAddProductOverlay = function() {
                        addProductPanel.classList.remove('overlay-open');

                        setTimeout(() => {
                            addProductOverlay.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                            // Reset form
                            document.getElementById('add-product-form').reset();
                            hideImagePreview();
                        }, 300);
                    };

                    addProductBtn.addEventListener('click', openAddProductOverlay);
                    closeAddProduct.addEventListener('click', closeAddProductOverlay);
                    cancelAddProduct.addEventListener('click', closeAddProductOverlay);

                    // Close overlay when clicking outside the panel
                    addProductOverlay.addEventListener('click', function(e) {
                        if (e.target === addProductOverlay) {
                            closeAddProductOverlay();
                        }
                    });

                    // Close overlay with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && !addProductOverlay.classList.contains('hidden')) {
                            closeAddProductOverlay();
                        }
                    });
                }

                // Image upload functionality
                if (uploadBtn && productImageInput) {
                    uploadBtn.addEventListener('click', function() {
                        productImageInput.click();
                    });

                    productImageInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                imagePreview.classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    removeImageBtn.addEventListener('click', function() {
                        hideImagePreview();
                        productImageInput.value = '';
                    });

                    function hideImagePreview() {
                        imagePreview.classList.add('hidden');
                        previewImg.src = '';
                    }

                    // Drag and drop functionality
                    const dropZone = uploadBtn.closest('.border-dashed');

                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, preventDefaults, false);
                    });

                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropZone.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, unhighlight, false);
                    });

                    function highlight(e) {
                        dropZone.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                    }

                    function unhighlight(e) {
                        dropZone.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
                    }

                    dropZone.addEventListener('drop', handleDrop, false);

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;

                        if (files.length > 0) {
                            productImageInput.files = files;
                            const changeEvent = new Event('change', { bubbles: true });
                            productImageInput.dispatchEvent(changeEvent);
                        }
                    }
                }

                // Filter Modal functionality with animations
                if (filterBtn && filterModal) {
                    filterBtn.addEventListener('click', function() {
                        filterModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';

                        // Trigger animation
                        setTimeout(() => {
                            filterModal.classList.add('modal-enter');
                            modalContent.classList.add('modal-content-enter');
                            modalContent.style.transform = 'scale(1)';
                            modalContent.style.opacity = '1';
                        }, 10);
                    });

                    const closeModalFunction = function() {
                        // Exit animation
                        modalContent.style.transform = 'scale(0.95)';
                        modalContent.style.opacity = '0';
                        filterModal.classList.remove('modal-enter');
                        modalContent.classList.remove('modal-content-enter');

                        setTimeout(() => {
                            filterModal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }, 300);
                    };

                    if (closeModal) closeModal.addEventListener('click', closeModalFunction);

                    // Close modal when clicking outside
                    filterModal.addEventListener('click', function(e) {
                        if (e.target === filterModal) {
                            closeModalFunction();
                        }
                    });

                    // Close modal with Escape key (only if add product overlay is not open)
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && !filterModal.classList.contains('hidden') && addProductOverlay.classList.contains('hidden')) {
                            closeModalFunction();
                        }
                    });
                }

                // Custom checkbox functionality
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const icon = this.nextElementSibling.querySelector('.checkbox-icon');
                        if (this.checked) {
                            icon.classList.remove('hidden');
                            this.nextElementSibling.classList.add('bg-blue-600', 'border-blue-600');
                            this.nextElementSibling.classList.remove('border-gray-300', 'dark:border-gray-600');
                        } else {
                            icon.classList.add('hidden');
                            this.nextElementSibling.classList.remove('bg-blue-600', 'border-blue-600');
                            this.nextElementSibling.classList.add('border-gray-300', 'dark:border-gray-600');
                        }
                    });

                    // Initialize state
                    const icon = checkbox.nextElementSibling.querySelector('.checkbox-icon');
                    if (checkbox.checked) {
                        icon.classList.remove('hidden');
                        checkbox.nextElementSibling.classList.add('bg-blue-600', 'border-blue-600');
                        checkbox.nextElementSibling.classList.remove('border-gray-300', 'dark:border-gray-600');
                    }
                });

                // Custom toggle functionality
                document.querySelectorAll('.toggle-checkbox').forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const bg = this.nextElementSibling;
                        const dot = bg.querySelector('.toggle-dot');

                        if (this.checked) {
                            bg.classList.add('bg-green-500');
                            bg.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                            dot.classList.add('translate-x-5');
                            dot.classList.remove('translate-x-0.5');
                        } else {
                            bg.classList.remove('bg-green-500');
                            bg.classList.add('bg-gray-200', 'dark:bg-gray-600');
                            dot.classList.remove('translate-x-5');
                            dot.classList.add('translate-x-0.5');
                        }
                    });

                    // Initialize state
                    const bg = toggle.nextElementSibling;
                    const dot = bg.querySelector('.toggle-dot');
                    if (toggle.checked) {
                        bg.classList.add('bg-green-500');
                        bg.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                        dot.classList.add('translate-x-5');
                        dot.classList.remove('translate-x-0.5');
                    }
                });

                // Reset filters functionality
                if (resetFilters) {
                    resetFilters.addEventListener('click', function() {
                        // Reset all form inputs
                        const form = document.getElementById('filter-form');

                        // Reset checkboxes
                        form.querySelectorAll('.category-checkbox').forEach(checkbox => {
                            checkbox.checked = false;
                            const icon = checkbox.nextElementSibling.querySelector('.checkbox-icon');
                            icon.classList.add('hidden');
                            checkbox.nextElementSibling.classList.remove('bg-blue-600', 'border-blue-600');
                            checkbox.nextElementSibling.classList.add('border-gray-300', 'dark:border-gray-600');
                        });

                        // Reset toggles
                        form.querySelectorAll('.toggle-checkbox').forEach(toggle => {
                            toggle.checked = false;
                            const bg = toggle.nextElementSibling;
                            const dot = bg.querySelector('.toggle-dot');
                            bg.classList.remove('bg-green-500');
                            bg.classList.add('bg-gray-200', 'dark:bg-gray-600');
                            dot.classList.remove('translate-x-5');
                            dot.classList.add('translate-x-0.5');
                        });

                        // Reset select
                        form.querySelector('select[name="sort_by"]').value = '';

                        // Reset hidden inputs
                        form.querySelectorAll('input[type="hidden"]').forEach(input => {
                            if (input.name !== 'search') {
                                input.value = '';
                            }
                        });
                    });
                }

                // Search on input
                if (searchInput) {
                    let searchTimeout;
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            this.form.submit();
                        }, 500);
                    });
                }

                // Bulk delete functionality
                if (checkboxes.length > 0 && bulkDeleteBtn) {
                    // Get both desktop and mobile select all checkboxes
                    const selectAllDesktop = document.getElementById('select-all-checkbox');
                    const selectAllMobile = document.getElementById('select-all-checkbox-mobile');

                    function toggleButtonState() {
                        let checkedCount = 0;
                        checkboxes.forEach(checkbox => {
                            if (checkbox.checked) {
                                checkedCount++;
                            }
                        });

                        // Update bulk delete button state
                        bulkDeleteBtn.disabled = checkedCount === 0;

                        // Update select all checkboxes based on individual checkbox states
                        const allChecked = checkedCount === checkboxes.length;
                        const someChecked = checkedCount > 0 && checkedCount < checkboxes.length;

                        // Update desktop select all checkbox
                        if (selectAllDesktop) {
                            selectAllDesktop.checked = allChecked;
                            selectAllDesktop.indeterminate = someChecked;
                        }

                        // Update mobile select all checkbox
                        if (selectAllMobile) {
                            selectAllMobile.checked = allChecked;
                            selectAllMobile.indeterminate = someChecked;
                        }

                        return checkedCount;
                    }

                    // Desktop select all functionality
                    if (selectAllDesktop) {
                        selectAllDesktop.addEventListener('click', function (event) {
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = event.target.checked;
                            });
                            toggleButtonState();
                        });
                    }

                    // Mobile select all functionality
                    if (selectAllMobile) {
                        selectAllMobile.addEventListener('click', function (event) {
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = event.target.checked;
                            });
                            toggleButtonState();
                        });
                    }

                    // Individual checkbox functionality
                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('click', function() {
                            toggleButtonState();
                        });
                    });

                    // Bulk delete button click - opens custom modal
                    bulkDeleteBtn.addEventListener('click', function() {
                        const count = toggleButtonState();
                        if (count > 0) {
                            openBulkDeleteModal(count);
                        }
                    });

                    toggleButtonState(); // Initial check
                }

                // Close modals with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!deleteModal.classList.contains('hidden')) {
                            closeDeleteModalFunction();
                        } else if (!bulkDeleteModal.classList.contains('hidden')) {
                            closeBulkDeleteModalFunction();
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
