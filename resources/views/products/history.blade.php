<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Show the product name in the header --}}
            Purchase History for: {{ $productName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Search --}}
                    <form action="{{ route('products.history', ['productName' => $productName]) }}" method="GET" class="flex items-center space-x-2">
                        <x-text-input id="search" type="text" name="search" :value="request('search')" placeholder="Search by SKU..." />
                        <x-primary-button>Search</x-primary-button>
                    </form>

                    {{-- Filter button --}}
                    <div class="mb-4 flex justify-end">
                        <form action="{{ route('products.history', ['productName' => $productName]) }}" method="GET" class="flex items-center space-x-2">
                            <label for="sort_by" class="text-sm font-medium text-gray-700">Sort By:</label>
                            <select name="sort_by" id="sort_by" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                <option value="newest" @if(request('sort_by', 'newest') == 'newest') selected @endif>Newest First</option>
                                <option value="oldest" @if(request('sort_by') == 'oldest') selected @endif>Oldest First</option>
                            </select>
                        </form>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchase Date</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchases as $purchase)
                            <tr>
                                {{-- Display Product Image --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($purchase->product->product_image)
                                        <div class="h-16 w-16">
                                            <img src="{{ asset('storage/' . $purchase->product->product_image) }}" alt="{{ $purchase->product->product_name }}" class="h-full w-full object-contain">
                                        </div>
                                    @else
                                        <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded-md">
                                            <span class="text-xs text-gray-500">No img</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Product Name --}}
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $purchase->product->product_name }}</td>

                                {{-- We can access the product's SKU via the relationship --}}
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->product->product_SKU }}</td>

                                {{-- Display Category Name --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{-- We use optional() in case a product somehow has no category --}}
                                    {{ optional($purchase->product->category)->category_name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->stock_quantity }}</td>

                                {{-- We can access the product's price via the relationship --}}
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($purchase->product->product_price, 2) }}</td>

                                {{-- Display purchase date --}}
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($purchase->stock_purchase_date)->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No purchase history found for this product name.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
