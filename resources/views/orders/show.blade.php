<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800">Orders</a>
                <span class="text-gray-400 mx-2">/</span>
                Order #{{ $order->order_id }}
            </h2>

            <div class="flex items-center space-x-4">
                {{-- Export CSV --}}
                <div>
                    <a href="{{ route('orders.export', $order) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Export as CSV
                    </a>
                </div>

                {{-- Actions Dropdown --}}
                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-800 focus:outline-none transition ease-in-out duration-150">
                            <div>More Actions</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        {{-- Email customer --}}
                        <x-dropdown-link :href="'mailto:' . optional($order->customer)->customer_email . '?subject=Regarding Order %23' . $order->order_id">
                            {{ __('Email Customer') }}
                        </x-dropdown-link>

                        {{-- We will add other actions here --}}
                        @if($order->order_status !== 'Canceled')

                            {{-- Cancel order --}}
                            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                @csrf
                                <x-dropdown-link :href="route('orders.cancel', $order)"
                                                 onclick="event.preventDefault(); if(confirm('Are you sure?')) { this.closest('form').submit(); }">
                                    {{ __('Cancel Order') }}
                                </x-dropdown-link>
                            </form>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>


        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Top Section: Customer Info & Order Status --}}
                    <div class="flex justify-between items-start mb-6">

                        {{-- Left Side: Order ID and Customer --}}
                        <div>
                            <p class="text-sm text-gray-500">Order ID</p>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-4">#{{ $order->order_id }}</h3>

                            @if($order->customer)
                                <div class="flex items-center space-x-4">
                                    {{-- Customer Image --}}
                                    @if($order->customer->customer_image)
                                        <img src="{{ $order->customer->customer_image }}" alt="{{ $order->customer->customer_name }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xl text-gray-500">{{ substr($order->customer->customer_name, 0, 1) }}</span>
                                        </div>
                                    @endif

                                    {{-- Customer Details --}}
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order->customer->customer_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer->customer_email }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer->customer_phone }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Right Side: Order Date, Status and Total --}}
                        <div class="text-right">
                            <div>
                                <p class="text-sm text-gray-500">Order Date</p>
                                <p class="text-gray-900 mb-4">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Order Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->order_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $order->order_status }}</span>
                            </div>

                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- Middle Section: Order Items Table --}}
                    <h3 class="text-lg font-medium mb-4">Order Items</h3>
                    <div class="flow-root">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->product_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->product_SKU }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $item->order_item_quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($item->order_item_unit_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">${{ number_format($item->order_item_quantity * $item->order_item_unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bottom Section: Order Total Summary --}}
                    <div class="mt-6 flex justify-end">
                        <div class="w-full max-w-sm">
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Shipping</dt>
                                    <dd class="text-sm font-medium text-gray-900">$0.00</dd>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
