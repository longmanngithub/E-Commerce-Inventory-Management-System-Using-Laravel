<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Orders') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all customer orders</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="lg:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Orders') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all customer orders</h4>
            </div>


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6 md:p-12">

                    <div class="flex justify-between items-center mb-10">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('orders.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                Order Detail
                            </h2>
                        </div>

                        <button id="moreActionsBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                            </svg>
                            More
                        </button>
                    </div>

                    {{-- Top Section --}}
                    <div class="flex flex-col lg:flex-row justify-between items-start mb-8">
                        {{-- Left Side: Order & Customer Info --}}
                        <div class="mb-6 lg:mb-0">
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Order ID</p>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">#{{ $order['id'] }}</h1>
                            </div>

                            @if($order['customer'])
                                <div class="flex items-center space-x-4">
                                    {{-- Avatar --}}
                                    @if($order['customer']['imageUrl'])
                                        <img src="{{ $order['customer']['imageUrl'] }}" alt="{{ $order['customer']['name'] }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                                    @else
                                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                                            <span class="text-lg font-semibold text-white">{{ substr($order['customer']['name'], 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order['customer']['name'] ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order['customer']['email'] ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order['customer']['phone'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Right Side: Status & Total --}}
                        <div class="text-left lg:text-right space-y-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Order Date</p>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($order['date'])->format('m/d/Y') }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Order Status</p>
                                <div class="inline-flex items-center space-x-2">
                                    @if($order['status'] == 'Paid')
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $order['status'] }}</span>
                                    @elseif($order['status'] == 'Canceled')
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ $order['status'] }}</span>
                                    @else
                                        <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $order['status'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">USD {{ $order['totalAmount'] }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700 my-8">

                    {{-- Order Items Section --}}
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-6 gap-12">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Order Items</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Overview of all purchased products</p>
                            </div>

                            <a href="{{ route('orders.export', $order['id']) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export as CSV
                            </a>
                        </div>

                        {{-- Mobile Cards (visible on small screens) --}}
                        <div class="block md:hidden space-y-4">
                            @foreach($order['items'] as $item)
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                                    {{-- Product Header --}}
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-16 w-16 rounded-xl flex items-center justify-center">
                                                <img class="h-full w-full rounded-lg object-contain" src="{{ $item['imageUrl'] ?? '...' }}" alt="{{ $item['name'] }}">
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate text-wrap">{{ $item['name'] }}</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 text-wrap">SKU: {{ $item['sku'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="text-xl font-bold text-green-600 dark:text-green-400">USD {{ $item['price'] }}</p>
                                        </div>
                                    </div>

                                    {{-- Product Details --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a2 2 0 012-2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $item['category'] }}</span>
                                        </div>

                                        <div class="flex flex-col items-end space-y-4">
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Qty: {{ $item['quantity'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal</p>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">USD {{ $item['subtotal'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop Table (hidden on small screens) --}}
                        <div class="hidden md:block bg-gray-50 dark:bg-gray-900 rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($order['items'] as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                            <td class="px-6 py-6">
                                                <div class="flex items-center space-x-4">
                                                    {{-- Product Image Placeholder --}}
                                                    <div class="flex-shrink-0 h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                        <div class="h-10 w-10 flex-shrink-0">
                                                            <img class="h-10 w-10 rounded-md object-contain" src="{{ $item['imageUrl'] ?? '...' }}" alt="">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item['category'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                                {{ $item['sku'] }}
                                            </td>
                                            <td class="px-6 py-6 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white text-sm font-medium rounded-full">
                                                    {{ $item['quantity'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-6 text-right text-sm font-medium text-gray-900 dark:text-white">
                                                USD {{ $item['price'] }}
                                            </td>
                                            <td class="px-6 py-6 text-right text-lg font-semibold text-gray-900 dark:text-white">
                                                USD {{ $item['subtotal'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="flex justify-end">
                        <div class="w-full max-w-sm bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">USD {{ $order['totalAmount'] }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Shipping</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">USD 0.00</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tax</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">USD 0.00</dd>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <div class="flex justify-between">
                                        <dt class="text-lg font-semibold text-gray-900 dark:text-white">Total</dt>
                                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">USD {{ $order['totalAmount'] }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions Modal --}}
    <div id="actionsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div id="modalOverlay" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80"></div>

            {{-- Modal panel --}}
            <div id="modalPanel" class="inline-block w-full max-w-md p-0 my-8 text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <div class="p-6">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Actions</h3>
                        <button id="closeModal" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Actions --}}
                    <div class="space-y-4">
                        {{-- Email Customer --}}
                        <a href="mailto:{{ $order['customer']['email'] ?? '' }}?subject=Regarding Order %23{{ $order['id'] }}" class="w-full flex items-center justify-center px-6 py-3 bg-gray-900 dark:bg-gray-700 text-white font-medium rounded-xl hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 3.26a2 2 0 001.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Customer
                        </a>

                        {{-- Cancel Order --}}
                        @if($order['status'] !== 'Canceled')
                            <form id="cancelOrderForm" action="{{ route('orders.cancel', $order['id']) }}" method="POST" class="w-full">
                                @csrf
                                <button type="button" id="cancelOrderBtn" class="w-full flex items-center justify-center px-6 py-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                    </svg>
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('actionsModal');
            const modalPanel = document.getElementById('modalPanel');
            const modalOverlay = document.getElementById('modalOverlay');
            const moreBtn = document.getElementById('moreActionsBtn');
            const closeBtn = document.getElementById('closeModal');
            const cancelOrderBtn = document.getElementById('cancelOrderBtn');
            const cancelOrderForm = document.getElementById('cancelOrderForm');

            function openModal() {
                modal.classList.remove('hidden');

                // Add entrance animation
                requestAnimationFrame(() => {
                    modalOverlay.style.opacity = '0';
                    modalPanel.style.transform = 'scale(0.95) translateY(-10px)';
                    modalPanel.style.opacity = '0';

                    requestAnimationFrame(() => {
                        modalOverlay.style.transition = 'opacity 200ms ease-out';
                        modalPanel.style.transition = 'all 200ms ease-out';
                        modalOverlay.style.opacity = '1';
                        modalPanel.style.transform = 'scale(1) translateY(0)';
                        modalPanel.style.opacity = '1';
                    });
                });
            }

            function closeModal() {
                modalOverlay.style.transition = 'opacity 150ms ease-in';
                modalPanel.style.transition = 'all 150ms ease-in';
                modalOverlay.style.opacity = '0';
                modalPanel.style.transform = 'scale(0.95) translateY(-10px)';
                modalPanel.style.opacity = '0';

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
            }

            // Event listeners
            moreBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            modalOverlay.addEventListener('click', closeModal);

            // Cancel order button - submit form immediately without confirmation
            if (cancelOrderBtn && cancelOrderForm) {
                cancelOrderBtn.addEventListener('click', function() {
                    cancelOrderForm.submit();
                });
            }

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Prevent modal from closing when clicking inside the panel
            modalPanel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</x-app-layout>
