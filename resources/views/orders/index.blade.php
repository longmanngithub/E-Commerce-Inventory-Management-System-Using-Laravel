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


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Search and Filter Bar --}}
                    <div class="mb-6 flex flex-row gap-4 items-center justify-between">
                        <form action="{{ route('orders.index') }}" method="GET" class="flex-1 max-w-md" id="searchForm">
                            {{-- Preserve existing filters --}}
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if(request('sort_by'))
                                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            @endif
                            @if(request('paid_only'))
                                <input type="hidden" name="paid_only" value="{{ request('paid_only') }}">
                            @endif

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text"
                                       name="search"
                                       id="searchInput"
                                       placeholder="Search Order"
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-12 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

                                {{-- Search Button --}}
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    @if(request('search'))
                                        <button type="button"
                                                onclick="clearSearch()"
                                                class="h-full px-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="submit"
                                            class="h-full px-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none border-l border-gray-300 dark:border-gray-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <button id="filterButton"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-sm text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5" d="M21.25 12H8.895m-4.361 0H2.75m18.5 6.607h-5.748m-4.361 0H2.75m18.5-13.214h-3.105m-4.361 0H2.75m13.214 2.18a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm-9.25 6.607a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm6.607 6.608a2.18 2.18 0 1 0 0-4.361a2.18 2.18 0 0 0 0 4.36Z" />
                            </svg>
                            Filter
                        </button>
                    </div>

                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-tl-lg">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-tr-lg">Action</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150"
                                    onclick="window.location='{{ route('orders.show', $order['id']) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $order['id'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $order['customerName'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y, h:i:s A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        USD {{ $order['totalAmount'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order['status'] === 'Paid')
                                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Paid
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                Canceled
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="event.stopPropagation(); openActionsModal({{ $order['id'] }}, '{{ $order['customerEmail'] ?? '' }}', '{{ $order['status'] }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-100/20">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No orders found</p>
                                            <p class="text-sm">Try adjusting your search or filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards Layout --}}
                    <div class="md:hidden space-y-4">
                        @forelse($orders as $order)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 cursor-pointer transition-all duration-150 hover:shadow-md"
                                 onclick="window.location='{{ route('orders.show', $order['id']) }}'">

                                {{-- Header: Customer Name and Status --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $order['customerName'] ?? 'N/A' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Order #: {{ $order['id'] }}
                                        </p>
                                    </div>

                                    <div class="flex items-center space-x-2 ml-4">
                                        @if($order['status'] === 'Paid')
                                            <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                                Paid
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                                Canceled
                                            </span>
                                        @endif

                                        <button onclick="event.stopPropagation(); openActionsModal({{ $order['id'] }}, '{{ $order['customerEmail'] ?? '' }}', '{{ $order['status'] }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-100/20 transition-colors duration-150">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Date and Amount Row --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm">
                                            {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y, h:i:s A') }}
                                        </span>
                                    </div>

                                    <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                                        USD {{ $order['totalAmount'] }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No orders found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div id="filterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center">
        <div id="filterModalContent" class="relative mx-auto p-0 border-0 w-96 shadow-2xl rounded-2xl bg-white dark:bg-gray-800 transform scale-95 transition-transform duration-300 my-8">
            {{-- Header --}}
            <div class="flex items-center justify-between p-6 pb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Filter</h3>
                <button onclick="closeFilterModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 dark:border-gray-700 mx-6"></div>

            <form action="{{ route('orders.index') }}" method="GET" id="filterForm" class="p-6 pt-6">
                <input type="hidden" name="search" value="{{ request('search') }}">

                {{-- Availability --}}
                <div class="mb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Availability</h4>
                    <div class="space-y-1">
                        <label for="statusFilter" class="block text-sm text-gray-700 dark:text-gray-300 mb-3">Show paid only</label>
                        <div class="relative">
                            <select name="status" id="statusFilter" class="block w-full px-4 py-3 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-colors duration-150">
                                <option value="">All Orders</option>
                                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid Only</option>
                                <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled Only</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sort By --}}
                <div class="mb-10">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">Sort By</h4>
                    <div class="relative">
                        <select name="sort_by" id="sortBy" class="block w-full px-4 py-3 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-colors duration-150">
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price (Ascending)</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price (Descending)</option>
                            <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Date (Newest First)</option>
                            <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>Date (Oldest First)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex space-x-3">
                    <button type="button" onclick="resetFilters()" class="flex-1 px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-150">
                        Reset
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-150">
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Actions Modal --}}
    <div id="actionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden opacity-0 transition-opacity duration-300">
        <div id="actionsModalContent" class="relative top-20 mx-auto p-0 border-0 w-96 shadow-2xl rounded-2xl bg-white dark:bg-gray-800 transform scale-95 transition-transform duration-300">
            {{-- Header --}}
            <div class="flex items-center justify-between p-6 pb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Actions</h3>
                <button onclick="closeActionsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 dark:border-gray-700 mx-6"></div>

            <div class="p-6 pt-6">
                <div class="space-y-4">
                    {{-- Email Customer --}}
                    <button id="emailCustomerBtn"
                            class="w-full flex items-center justify-center px-6 py-4 bg-gray-800 dark:bg-gray-700 text-white rounded-xl text-sm font-semibold hover:bg-gray-900 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-150 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Customer
                    </button>

                    {{-- Cancel Order --}}
                    <button id="cancelOrderBtn"
                            class="w-full flex items-center justify-center px-6 py-4 bg-white dark:bg-gray-700 border-2 border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 rounded-xl text-sm font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 dark:hover:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-150 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cancel Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;
        let currentOrderEmail = null;
        let currentOrderStatus = null;

        // Filter Modal Functions
        function openFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = document.getElementById('filterModalContent');

            modal.classList.remove('hidden');

            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = document.getElementById('filterModalContent');

            // Start closing animation
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            // Hide after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('sortBy').value = 'date_desc';

            // Redirect to clean URL
            window.location.href = '{{ route("orders.index") }}';
        }

        // Search Functions
        function clearSearch() {
            const form = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            form.submit();
        }

        // Actions Modal Functions
        function openActionsModal(orderId, orderEmail, orderStatus) {
            currentOrderId = orderId;
            currentOrderEmail = orderEmail;
            currentOrderStatus = orderStatus;

            // Show/hide buttons based on order status and email availability
            const emailBtn = document.getElementById('emailCustomerBtn');
            const cancelBtn = document.getElementById('cancelOrderBtn');

            if (!orderEmail) {
                emailBtn.style.display = 'none';
            } else {
                emailBtn.style.display = 'flex';
            }

            if (orderStatus === 'Canceled') {
                cancelBtn.style.display = 'none';
            } else {
                cancelBtn.style.display = 'flex';
            }

            const modal = document.getElementById('actionsModal');
            const modalContent = document.getElementById('actionsModalContent');

            modal.classList.remove('hidden');

            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeActionsModal() {
            const modal = document.getElementById('actionsModal');
            const modalContent = document.getElementById('actionsModalContent');

            // Start closing animation
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            // Hide after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Event Listeners
        document.getElementById('filterButton').addEventListener('click', openFilterModal);

        document.getElementById('emailCustomerBtn').addEventListener('click', function() {
            if (currentOrderEmail) {
                window.location.href = `mailto:${currentOrderEmail}?subject=Regarding Order %23${currentOrderId}`;
            }
        });

        // Cancel Order
        document.getElementById('cancelOrderBtn').addEventListener('click', function() {
            if (!currentOrderId) return; // Safety check

            const form = document.createElement('form');
            form.method = 'POST';

            let urlTemplate = '{{ route("orders.cancel", ["order" => ":id"]) }}';
            form.action = urlTemplate.replace(':id', currentOrderId);

            // Add the CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add the form to the page, submit it, and then remove it
            document.body.appendChild(form);
            form.submit();
        });

        // Close modals when clicking outside
        document.getElementById('filterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });

        document.getElementById('actionsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeActionsModal();
            }
        });

        // Update filter form search value when applying filters
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const searchValue = document.getElementById('searchInput').value;
            const hiddenSearch = document.querySelector('#filterForm input[name="search"]');
            hiddenSearch.value = searchValue;
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFilterModal();
                closeActionsModal();
            }
        });
    </script>
</x-app-layout>
