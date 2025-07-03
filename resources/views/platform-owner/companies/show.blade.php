<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Company Overview') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company information</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="flex justify-between items-center mb-8 gap-x-3">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    <a href="{{ route('owner.dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        &larr; {{ $companyData['name'] }}
                    </a>
                </h2>

                {{-- Actions Header --}}
                <div>
                    {{-- Conditionally show the Reactivate button --}}
                    @if($companyData['status'] === 'Inactive')
                        <button type="button" onclick="openReactivateModal()" class="inline-flex items-center px-2 md:px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-base text-white hover:bg-green-500 dark:bg-green-700 dark:hover:bg-green-600 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21.5 9h-5l1.86-1.86A7.99 7.99 0 0 0 12 4c-4.42 0-8 3.58-8 8c0 1.83.61 3.5 1.64 4.85c1.22-1.4 3.51-2.35 6.36-2.35s5.15.95 6.36 2.35A7.95 7.95 0 0 0 20 12h2c0 5.5-4.5 10-10 10S2 17.5 2 12S6.5 2 12 2c3.14 0 5.95 1.45 7.78 3.72L21.5 4zM12 7c1.66 0 3 1.34 3 3s-1.34 3-3 3s-3-1.34-3-3s1.34-3 3-3" />
                            </svg>
                            Reactivate
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left Column - Company Logo & Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 space-y-6">
                        {{-- Company Logo --}}
                        <div class="flex">
                            @if($companyData['imageUrl'])
                                <img src="{{ $companyData['imageUrl'] }}" alt="{{ $companyData['name'] }}" class="w-72 lg:w-full h-full rounded-lg object-contain">
                            @else
                                <div class="w-72 lg:w-full aspect-square bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-2xl">{{ substr($companyData['name'], 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Description --}}
                        <div class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $companyData['desc'] ?? 'No description provided.' }}
                        </div>

                        {{-- Company Info --}}
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Company ID:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['id'] }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $companyData['status'] === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 {{ $companyData['status'] === 'Active' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-1"></div>
                                    {{ $companyData['status'] }}
                                </span>
                            </div>

                            @if($companyData['subscription'])
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Subscription Plan:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['subscription']['plan'] }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 dark:text-gray-400">Subscription Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $companyData['subscription']['status'] === 'Paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        <div class="w-2 h-2 {{ $companyData['subscription']['status'] === 'Paid' ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full mr-1"></div>
                                        {{ $companyData['subscription']['status'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column - Metrics & Details --}}
                <div class="lg:col-span-3 space-y-8">

                    {{-- Key Metrics Cards --}}
                    <div class="flex md:grid grid-cols-1 md:grid-cols-3 gap-6 overflow-x-auto">
                        {{-- All Products --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 min-w-48">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">All Products</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $companyData['analytics']['allProducts'] }}</div>
                        </div>

                        {{-- Revenue --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 min-w-max">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">Revenue</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">+ {{ number_format($companyData['analytics']['revenue'], 1) }}%</div>
                            <div class="text-{{ $companyData['analytics']['revenueChange'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $companyData['analytics']['revenueChange'] >= 0 ? 'green' : 'red' }}-400 text-sm font-medium">
                                {{ $companyData['analytics']['revenueChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['revenueChange']), 1) }}% from last month
                            </div>
                        </div>

                        {{-- Sales --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 min-w-max">
                            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-2">Sales</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">+ {{ number_format($companyData['analytics']['sales'], 1) }}%</div>
                            <div class="text-{{ $companyData['analytics']['salesChange'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $companyData['analytics']['salesChange'] >= 0 ? 'green' : 'red' }}-400 text-sm font-medium">
                                {{ $companyData['analytics']['salesChange'] >= 0 ? '↑' : '↓' }} {{ number_format(abs($companyData['analytics']['salesChange']), 1) }}% from last month
                            </div>
                        </div>
                    </div>

                    {{-- Company Details Section --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Company Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 text-base">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Company Name</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['name'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Address</span>
                                    <span class="font-medium text-gray-900 dark:text-white text-right max-w-xs">{{ $companyData['companyDetails']['address'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Company Email</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['email'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Next Billing Cycle</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['nextBilling'] ? \Carbon\Carbon::parse($companyData['companyDetails']['nextBilling'])->format('F d, Y') : 'N/A' }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Member Since</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $companyData['companyDetails']['memberSince'] ? \Carbon\Carbon::parse($companyData['companyDetails']['memberSince'])->format('F d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Best Selling Products Section --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Best Selling Products</h3>

                        {{-- Mobile Layout (Cards) --}}
                        <div class="block md:hidden space-y-4">
                            @forelse($companyData['bestSellingProducts'] as $product)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                                    {{-- Product Name and Revenue Badge --}}
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">
                                            {{ $product['product_name'] }}
                                        </h4>
                                        <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-sm font-medium ml-3 flex-shrink-0">
                                            + {{ number_format($product['revenue'], 1) }}%
                                        </div>
                                    </div>

                                    {{-- Category and Sales --}}
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $product['category_name'] }}
                                        </div>
                                        <div class="flex items-center text-green-600 dark:text-green-400 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                            </svg>
                                            + {{ number_format($product['revenue'], 1) }}% sales
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                                    <div class="text-gray-400 dark:text-gray-500 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2C4.477 2 0 6.477 0 12s4.477 10 10 10 10-4.477 10-10S15.523 2 10 2zM8 11a1 1 0 100-2 1 1 0 000 2zm4 0a1 1 0 100-2 1 1 0 000 2zm-2 3a3 3 0 01-2.83-2H6a1 1 0 100-2h1.17a3 3 0 015.66 0H14a1 1 0 100 2h-1.17A3 3 0 0110 14z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No product sales data for this company.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Desktop Layout (Table) --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product Name</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                                    <th class="text-left py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sales</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($companyData['bestSellingProducts'] as $product)
                                    <tr>
                                        <td class="py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product['product_name'] }}</td>
                                        <td class="py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product['category_name'] }}</td>
                                        <td class="py-4 text-sm font-semibold text-green-600 dark:text-green-400">+{{ number_format($product['revenue'], 1) }}%</td>
                                        <td class="py-4 text-sm font-semibold text-green-600 dark:text-green-400">+{{ number_format($product['revenue'], 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-sm text-center text-gray-500 dark:text-gray-400">No product sales data for this company.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Custom Reactivate Modal --}}
    <div id="reactivateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity duration-300 ease-out opacity-0"></div>

            <!-- Modal positioning -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div id="modalPanel" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 translate-y-4 opacity-0 scale-95">
                <!-- Modal Header -->
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title">
                            Reactivate Company?
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to reactivate <strong class="font-medium text-gray-700 dark:text-gray-300">{{ $companyData['name'] }}</strong>? This action will make the company active and restore all services. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-8 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                    <form id="reactivateForm" action="{{ route('company.reactivate', $companyData['id']) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-600 dark:focus:ring-offset-gray-800 transition-colors duration-200 sm:w-auto sm:text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Reactivate
                        </button>
                    </form>
                    <button type="button" onclick="closeReactivateModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors duration-200 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal JavaScript --}}
    <script>
        function openReactivateModal() {
            const modal = document.getElementById('reactivateModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Trigger animations
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            }, 10);
        }

        function closeReactivateModal() {
            const modal = document.getElementById('reactivateModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Trigger exit animations
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'scale-95');

            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('modalBackdrop').addEventListener('click', closeReactivateModal);

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('reactivateModal');
                if (!modal.classList.contains('hidden')) {
                    closeReactivateModal();
                }
            }
        });

        // Prevent form submission animation delay
        document.getElementById('reactivateForm').addEventListener('submit', function() {
            // Optional: Add loading state to button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Reactivating...';
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>
