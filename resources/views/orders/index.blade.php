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

                                <div class="md:col-span-2">
                                    <label for="search" class="block font-medium text-sm text-gray-700">Search</label>
                                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" placeholder="Order # or Customer Name..." />
                                </div>

                                {{-- Status Filter --}}
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Status</label>
                                    <div class="mt-2">
                                        <label for="show_paid_only" class="inline-flex items-center">
                                            <input type="checkbox" id="show_paid_only" name="show_paid_only" value="1" @if(request('show_paid_only')) checked @endif class="rounded h-4 w-4 text-indigo-600">
                                            <span class="ms-2 text-sm text-gray-600">Show paid only</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sort By Filter --}}
                                <div>
                                    <label for="sort_by" class="block font-medium text-sm text-gray-700">Sort By</label>
                                    <select name="sort_by" id="sort_by" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="date_desc" @if(request('sort_by', 'date_desc') == 'date_desc') selected @endif>Newest First</option>
                                        <option value="date_asc" @if(request('sort_by') == 'date_asc') selected @endif>Oldest First</option>
                                        <option value="total_desc" @if(request('sort_by') == 'total_desc') selected @endif>Total: High to Low</option>
                                        <option value="total_asc" @if(request('sort_by') == 'total_asc') selected @endif>Total: Low to High</option>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $order->order_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ optional($order->customer)->customer_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColor = $order->order_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                            {{ $order->order_status }}
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
                                            <x-dropdown-link :href="route('orders.show', $order)">
                                                {{ __('View Details') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="'mailto:' . optional($order->customer)->customer_email . '?subject=Regarding Order %23' . $order->order_id">
                                                {{ __('Email Customer') }}
                                            </x-dropdown-link>

                                            {{-- Show Cancel button only if the order is not already canceled --}}
                                            @if($order->order_status !== 'Canceled')
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                                    @csrf
                                                    <x-dropdown-link :href="route('orders.cancel', $order)"
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
