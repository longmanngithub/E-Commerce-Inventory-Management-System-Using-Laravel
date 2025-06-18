<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Logs') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <form action="{{ route('management.logs.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="search" class="block font-medium text-sm text-gray-700">Search in log details...</label>
                                    <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                                </div>
                                <div>
                                    <label for="action" class="block font-medium text-sm text-gray-700">Action Type</label>
                                    <select name="action" id="action" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Actions</option>
                                        <option value="Created" @selected(request('action') == 'Created')>Created</option>
                                        <option value="Updated" @selected(request('action') == 'Updated')>Updated</option>
                                        <option value="Deleted" @selected(request('action') == 'Deleted')>Deleted</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <x-primary-button>Filter</x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Audit Log Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $log['userName'] ?? 'System' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            // Color-code the badge based on the action type
                                            $actionColor = match($log['action']) {
                                                'Created' => 'bg-green-100 text-green-800',
                                                'Updated' => 'bg-blue-100 text-blue-800',
                                                'Deleted' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                                                {{ $log['action'] }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log['entity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($log['timestamp'])->format('d M Y, h:i A') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $log['detail'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No log entries found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
