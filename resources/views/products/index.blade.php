<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>

            @can('create-product')
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Add Product
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Filter button --}}
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                {{-- Filter by Category --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Category</label>
                                    {{-- We need to pass $categories to this view from the controller --}}
                                    @foreach ($categories as $category)
                                        <div class="mt-1">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="categories[]" value="{{ $category['id'] }}"
                                                       @if(in_array($category['id'], request('categories', []))) checked @endif
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                                <span class="ms-2 text-sm text-gray-700">{{ $category['name'] }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Filter by Availability --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Availability</label>
                                    <div class="mt-1">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="in_stock_only" value="1" @if(request('in_stock_only')) checked @endif class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                            <span class="ms-2 text-sm text-gray-700">Show in-stock only</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort By --}}
                                <div>
                                    <label for="sort_by" class="block font-medium text-sm text-gray-700">Sort By</label>
                                    <select name="sort_by" id="sort_by" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Default</option>
                                        <option value="price_asc" @if(request('sort_by') == 'price_asc') selected @endif>Price: Low to High</option>
                                        <option value="price_desc" @if(request('sort_by') == 'price_desc') selected @endif>Price: High to Low</option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <x-primary-button>Filter</x-primary-button>
                                </div>

                                {{-- SEARCH --}}
                                <div>
                                    <label for="search" class="block font-medium text-sm text-gray-700">Search</label>
                                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" placeholder="Product name or SKU..." />
                                </div>

                            </div>
                        </form>
                    </div>

                    {{-- This checks if the user has permission to bulk delete BEFORE showing the form --}}
                    @can('bulk-delete-products')
                    <form id="bulk-delete-form" action="{{ route('products.bulkDestroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all selected products? This action cannot be undone.');">
                        @csrf

                        <div class="mb-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 disabled:opacity-50" id="bulk-delete-btn" disabled>
                                Delete Selected
                            </button>
                        </div>

                    </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">
                                        <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                    <tr>
                                        {{-- Checkbox --}}
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="product_ids[]" value="{{ $product['id'] }}" class="product-checkbox rounded border-gray-300 text-indigo-600 shadow-sm" form="bulk-delete-form">
                                        </td>

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

                                        {{-- Product name --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $product['name'] }}</td>

                                        {{-- Product SKU --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['sku'] }}</td>

                                        {{-- Display Category Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- We use optional() in case a product somehow has no category --}}
                                            {{ $product['category'] }}
                                        </td>

                                        {{-- Product price --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product['price'], 2) }}</td>

                                        {{-- Display Stock Quantity --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                            {{-- This line is crucial. It sums the stock ONLY for the current $product in the loop. --}}
                                            {{ $product['stockQuantity'] }}

                                        </td>

                                        {{-- Status Cell --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('products.toggleStatus', $product['id']) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                                {{ $product['status'] }}
                                            </a>
                                        </td>

                                        {{-- Availability status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Set the color based on the status
                                                $statusColor = match($product['stockStatus']) {
                                                    'In Stock' => 'bg-green-100 text-green-800',
                                                    'Low Stock' => 'bg-yellow-100 text-yellow-800',
                                                    'Out of Stock' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ $product['stockStatus'] }}
                                    </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            <div class="flex items-center space-x-4">

                                                {{-- The "View" link is available to everyone --}}
                                                <a href="{{ route('products.show', $product['id']) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>

                                                {{-- Only show the "Edit" link if the user is authorized by the 'update-product' Gate --}}
                                                @if($product['permissions']['update'])
                                                    <a href="{{ route('products.edit', $product['id']) }}?{{ http_build_query(request()->query()) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                @endif

                                                {{-- Only show the "Delete" form if the user is authorized by the 'delete-product' Gate --}}
                                                @if($product['permissions']['delete'])
                                                    <form action="{{ route('products.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                        {{-- END OF ACTIONS --}}

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No products found. Add your first product to get started.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>

                    {{-- END OF WRAPPING FORM --}}

                    @else
                        <div class="overflow-x-auto">
                            {{-- If the user CANNOT bulk delete, show the table WITHOUT the form or checkboxes --}}
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
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

                                        {{-- Product name --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $product['name'] }}</td>

                                        {{-- Product SKU --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['sku'] }}</td>

                                        {{-- Display Category Name --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- We use optional() in case a product somehow has no category --}}
                                            {{ $product['category'] }}
                                        </td>

                                        {{-- Product price --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product['price'], 2) }}</td>

                                        {{-- Display Stock Quantity --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                            {{-- This line is crucial. It sums the stock ONLY for the current $product in the loop. --}}
                                            {{ $product['stockQuantity'] }}

                                        </td>

                                        {{-- Status Cell --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('products.toggleStatus', $product['id']) }}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product['status'] === 'Active' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                                {{ $product['status'] }}
                                            </a>
                                        </td>

                                        {{-- Availability status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Set the color based on the status
                                                $statusColor = match($product['stockStatus']) {
                                                    'In Stock' => 'bg-green-100 text-green-800',
                                                    'Low Stock' => 'bg-yellow-100 text-yellow-800',
                                                    'Out of Stock' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ $product['stockStatus'] }}
                                    </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            {{-- NEW: A Flexbox container to align all items --}}
                                            <div class="flex items-center space-x-4">

                                                {{-- The "View" link is available to everyone --}}
                                                <a href="{{ route('products.show', $product['id']) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>

                                                {{-- Only show the "Edit" link if the user is authorized by the 'update-product' Gate --}}
                                                @if($product['permissions']['update'])
                                                    <a href="{{ route('products.edit', $product['id']) }}?{{ http_build_query(request()->query()) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                @endif

                                                {{-- Only show the "Delete" form if the user is authorized by the 'delete-product' Gate --}}
                                                @if($product['permissions']['delete'])
                                                    <form action="{{ route('products.destroy', $product['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                        {{-- END OF ACTIONS --}}

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No products found. Add your first product to get started.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endcan

                    {{-- Custom Pagination for API responses --}}
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Script for both "Select All" and the new single delete function --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all-checkbox');
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

            function toggleButtonState() {
                let checkedCount = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        checkedCount++;
                    }
                });
                bulkDeleteBtn.disabled = checkedCount === 0;
            }

            selectAll.addEventListener('click', function (event) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                });
                toggleButtonState();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    toggleButtonState();
                });
            });

            toggleButtonState(); // Initial check
        });
    </script>
    @endpush
</x-app-layout>
