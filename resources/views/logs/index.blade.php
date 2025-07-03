<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Logs') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all actions from company users</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="sm:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Logs') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View all actions from company users</h4>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Search and Filter Section --}}
                    <div class="mb-6 flex flex-row gap-4 justify-between items-center">
                        {{-- Search Form --}}
                        <form id="search-form" action="{{ route('management.logs.index') }}" method="GET" class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <x-text-input
                                id="search"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm"
                                type="text"
                                name="search"
                                :value="request('search')"
                                placeholder="Search Logs"
                            />
                            {{-- Hidden inputs to preserve existing filters --}}
                            <input type="hidden" name="action" value="{{ request('action') }}">
                        </form>

                        {{-- Filter Button --}}
                        <button
                            type="button"
                            onclick="openFilterModal()"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="1.5" d="M21.25 12H8.895m-4.361 0H2.75m18.5 6.607h-5.748m-4.361 0H2.75m18.5-13.214h-3.105m-4.361 0H2.75m13.214 2.18a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm-9.25 6.607a2.18 2.18 0 1 0 0-4.36a2.18 2.18 0 0 0 0 4.36Zm6.607 6.608a2.18 2.18 0 1 0 0-4.361a2.18 2.18 0 0 0 0 4.36Z" />
                            </svg>
                            Filter
                        </button>
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block overflow-x-auto shadow ring-1 ring-black ring-opacity-5 dark:ring-gray-600 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Entity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log['userName'] ?? 'System' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            // Color-code the badge based on the action type
                                            $actionColor = match($log['action']) {
                                                'Add', 'Created' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'Update', 'Updated' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'Delete', 'Deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                'Export' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-medium rounded {{ $actionColor }}">
                                            {{ $log['action'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $log['entity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($log['timestamp'])->format('d/m/Y, H:i:s') }}PM</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $log['detail'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No log entries found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($logs as $log)
                            @php
                                // Color-code the badge based on the action type
                                $actionColor = match($log['action']) {
                                    'Add', 'Created' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'Update', 'Updated' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'Delete', 'Deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'Export' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                };
                            @endphp
                            <div
                                class="bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 cursor-pointer hover:shadow-md transition-shadow duration-200"
                                onclick="openLogDetailModal('{{ $log['userName'] ?? 'System' }}', '{{ $log['action'] }}', '{{ $log['entity'] }}', '{{ \Carbon\Carbon::parse($log['timestamp'])->format('d/m/Y, H:i:s') }}PM', '{{ addslashes($log['detail']) }}')"
                            >
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $log['userName'] ?? 'System' }}</h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $actionColor }}">
                                        {{ $log['action'] }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span class="font-medium">Entity:</span> {{ $log['entity'] }}
                                </div>

                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($log['timestamp'])->format('d/m/Y, H:i:s') }}PM
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No log entries found.
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination Section --}}
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 opacity-0 invisible transition-all duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="modalContent" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md mx-auto transform scale-95 transition-all duration-300 ease-out">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Filter</h2>
                    <button type="button" onclick="closeFilterModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form id="filter-form" action="{{ route('management.logs.index') }}" method="GET">
                    <div class="p-6 space-y-6">
                        {{-- Action Type Selection --}}
                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action Type</label>
                            <select name="action" id="action" class="block w-full px-4 py-3 text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none">
                                <option value="">All Actions</option>
                                <option value="Created" @selected(request('action') == 'Created')>Created</option>
                                <option value="Updated" @selected(request('action') == 'Updated')>Updated</option>
                                <option value="Deleted" @selected(request('action') == 'Deleted')>Deleted</option>
                            </select>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex gap-4 p-6 pt-0">
                        <button type="button" onclick="resetFilters()" class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-transparent border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 font-medium">
                            Reset
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors duration-200 font-medium">
                            Apply
                        </button>
                    </div>

                    {{-- Hidden search input --}}
                    <input type="hidden" name="search" id="hidden-search" value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    {{-- Log Detail Modal (Mobile Only) --}}
    <div id="logDetailModal" class="md:hidden fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 opacity-0 invisible transition-all duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="logDetailModalContent" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md mx-auto transform scale-95 transition-all duration-300 ease-out">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Log Details</h2>
                    <button type="button" onclick="closeLogDetailModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <h3 id="modalUserName" class="font-semibold text-lg text-gray-900 dark:text-gray-100"></h3>
                            <span id="modalActionBadge" class="px-2 py-1 text-xs font-medium rounded"></span>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Entity:</span> <span id="modalEntity"></span>
                        </div>

                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="modalTimestamp"></span>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Details:</h4>
                            <p id="modalDetails" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = document.getElementById('modalContent');

            // Update hidden search field with current search value
            const searchInput = document.getElementById('search');
            const hiddenSearch = document.getElementById('hidden-search');
            hiddenSearch.value = searchInput.value;

            modal.classList.remove('invisible');
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');

            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeFilterModal() {
            const modal = document.getElementById('filterModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                modal.classList.add('invisible');
            }, 200);
        }

        function openLogDetailModal(userName, action, entity, timestamp, details) {
            const modal = document.getElementById('logDetailModal');
            const modalContent = document.getElementById('logDetailModalContent');

            // Populate modal content
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalEntity').textContent = entity;
            document.getElementById('modalTimestamp').textContent = timestamp;
            document.getElementById('modalDetails').textContent = details;

            // Set action badge with appropriate color
            const badge = document.getElementById('modalActionBadge');
            badge.textContent = action;

            // Remove all existing color classes
            badge.className = 'px-2 py-1 text-xs font-medium rounded';

            // Add appropriate color based on action
            const actionColorClasses = {
                'Add': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'Created': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'Update': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'Updated': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'Delete': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'Deleted': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'Export': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
            };

            const colorClasses = actionColorClasses[action] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
            badge.className += ' ' + colorClasses;

            modal.classList.remove('invisible');
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');

            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeLogDetailModal() {
            const modal = document.getElementById('logDetailModal');
            const modalContent = document.getElementById('logDetailModalContent');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                modal.classList.add('invisible');
            }, 200);
        }

        function resetFilters() {
            // Clear all form fields
            document.getElementById('search').value = '';
            document.getElementById('hidden-search').value = '';
            document.getElementById('action').value = '';

            // Submit the form
            document.getElementById('filter-form').submit();
        }

        // Close modal when clicking outside
        document.getElementById('filterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });

        // Close log detail modal when clicking outside
        document.getElementById('logDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogDetailModal();
            }
        });

        // Handle search form submission
        document.getElementById('search-form').addEventListener('submit', function(e) {
            // Form will submit naturally to perform search
        });

        // Handle search input - submit form on Enter
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('search-form').submit();
            }
        });

        // Handle Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFilterModal();
                closeLogDetailModal();
            }
        });
    </script>
</x-app-layout>
