<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Filter --}}
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('orders.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                {{-- Search --}}
                                <div class="md:col-span-2">
                                    <label for="search" class="block font-medium text-sm text-gray-700">Search</label>
                                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" placeholder="Order # or Customer Name..." />
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All</option>
                                        <option value="Paid" @selected(request('status') == 'Paid')>Paid</option>
                                        <option value="Canceled" @selected(request('status') == 'Canceled')>Canceled</option>
                                    </select>
                                </div>

                                {{-- Sort --}}
                                <div>
                                    <label for="sort_by" class="block font-medium text-sm text-gray-700">Sort By</label>
                                    <select name="sort_by" id="sort_by" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="date_desc" @selected(request('sort_by') == 'date_desc')>Date: Newest to Oldest</option>
                                        <option value="date_asc" @selected(request('sort_by') == 'date_asc')>Date: Oldest to Newest</option>
                                        <option value="price_desc" @selected(request('sort_by') == 'price_desc')>Price: High to Low</option>
                                        <option value="price_asc" @selected(request('sort_by') == 'price_asc')>Price: Low to High</option>
                                    </select>
                                </div>

                                <div class="flex items-end space-x-2">
                                    <x-primary-button>Apply</x-primary-button>
                                    <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $order['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $order['customerName'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($order['totalAmount'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = $order['status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ $order['status'] }}
                                        </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <div>Actions</div>
                                                <div class="ms-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('orders.show', $order['id'])">
                                                {{ __('View Details') }}
                                            </x-dropdown-link>
                                            @if(isset($order['customerEmail']))
                                                <x-dropdown-link :href="'mailto:' . $order['customerEmail'] . '?subject=Regarding Order %23' . $order['id']">
                                                    {{ __('Email Customer') }}
                                                </x-dropdown-link>
                                            @endif

                                            @if($order['status'] !== 'Canceled')
                                                <form action="{{ route('orders.cancel', $order['id']) }}" method="POST">
                                                    @csrf
                                                    <x-dropdown-link :href="route('orders.cancel', $order['id'])"
                                                                     onclick="event.preventDefault();
                                                            if(confirm('Are you sure you want to cancel this order?')) {
                                                                this.closest('form').submit();
                                                            }">
                                                        {{ __('Cancel Order') }}
                                                    </x-dropdown-link>
                                                </form>
                                            @endif
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
