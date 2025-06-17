<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800">Orders</a>
                <span class="text-gray-400 mx-2">/</span>
                Order #{{ $order['id'] }}
            </h2>

            <div class="flex items-center space-x-4">
                {{-- Export CSV --}}
                <a href="{{ route('orders.export', $order['id']) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Export as CSV
                </a>

                {{-- Actions Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-800 focus:outline-none transition">
                            <span>More Actions</span>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0L5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="'mailto:' . ($order['customer']['email']) . '?subject=Regarding Order %23' . $order['id']">
                            {{ __('Email Customer') }}
                        </x-dropdown-link>

                        @if($order['status'] !== 'Canceled')
                            <form action="{{ route('orders.cancel', $order['id']) }}" method="POST">
                                @csrf
                                <x-dropdown-link href="#" onclick="event.preventDefault(); if(confirm('Are you sure?')) this.closest('form').submit();">
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
                    {{-- Top Section --}}
                    <div class="flex flex-col md:flex-row justify-between mb-6">
                        {{-- Order & Customer Info --}}
                        <div class="mb-4 md:mb-0">
                            <p class="text-sm text-gray-500">Order ID</p>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-4">#{{ $order['id'] }}</h3>

                            @if($order['customer'])
                                <div class="flex items-center space-x-4">
                                    {{-- Avatar --}}
                                    @if($order['customer']['imageUrl'])
                                        <img src="{{ $order['customer']['imageUrl'] }}" alt="{{ $order['customer']['name'] }}" class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-xl text-gray-500">{{ substr($order['customer']['name'], 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order['customer']['name'] ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $order['customer']['email'] ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $order['customer']['phone'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Order Status --}}
                        <div class="text-right">
                            <div>
                                <p class="text-sm text-gray-500">Order Date</p>
                                <p class="text-gray-900 mb-4">{{ \Carbon\Carbon::parse($order['date'])->format('d M Y') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Order Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order['status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $order['status'] }}
                                </span>
                            </div>

                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($order['totalAmount'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- Order Items --}}
                    <h3 class="text-lg font-medium mb-4">Order Items</h3>
                    <div class="flow-root overflow-x-auto">
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
                            @foreach($order['items'] as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $item['sku'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-right">
                                        ${{ number_format($item['price'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right font-medium">
                                        ${{ number_format($item['quantity'] * $item['price'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Order Summary --}}
                    <div class="mt-6 flex justify-end">
                        <div class="w-full max-w-sm">
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">${{ number_format($order['totalAmount'], 2) }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Shipping</dt>
                                    <dd class="text-sm font-medium text-gray-900">$0.00</dd>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($order['totalAmount'], 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
