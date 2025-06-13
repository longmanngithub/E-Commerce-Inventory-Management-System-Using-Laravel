<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Search and Filter Section --}}
                    <div class="flex justify-between items-center mb-4">
                        {{-- Search Form --}}
                        <form action="{{ route('management.logs.index') }}" method="GET" class="w-1/3">
                            <div class="flex">
                                <x-text-input id="search" class="block w-full rounded-none rounded-l-md" type="text" name="search" :value="request('search')" placeholder="Search in log details..." />
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Search
                                </button>
                            </div>
                        </form>

                        {{-- Filter Button --}}
                        <a href="#"
                           x-data
                           @click.prevent="$dispatch('open-modal', 'filter-logs')"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            <span>Filter</span>
                        </a>

                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Staff Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Entity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Detail
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $log->user->staff_name ?? $log->user->admin_name ?? 'Unknown User' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- This block sets the color class based on the action text --}}
                                    @php
                                        $actionColor = match(strtolower($log->action)) {
                                            'created' => 'bg-blue-100 text-blue-800',
                                            'updated' => 'bg-green-100 text-green-800',
                                            'deleted' => 'bg-red-100 text-red-800',
                                            'exported' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColor }}">
                        {{ $log->action }}
                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->entity_affected }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->timestamp)->format('d/m/Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->details }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No log entries found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Filter Modal --}}
    <x-modal name="filter-logs" focusable>
        <form action="{{ route('management.logs.index') }}" method="GET" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Filter Logs</h2>

            {{-- Filter by Action --}}
            <div class="mt-6">
                <h3 class="text-md font-medium text-gray-800">Action</h3>
                <div class="mt-2 space-y-2">
                    @foreach(['Created', 'Updated', 'Deleted', 'Exported'] as $action)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="actions[]" value="{{ $action }}" @if(in_array($action, request('actions', []))) checked @endif class="rounded ...">
                            <span class="ms-2 text-sm text-gray-700">Show {{ strtolower($action) }} only</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Sort By --}}
            <div class="mt-6">
                <x-input-label for="sort_by" :value="__('Sort By')" />
                <select name="sort_by" id="sort_by" class="block mt-1 w-full ...">
                    <option value="newest" @if(request('sort_by', 'newest') == 'newest') selected @endif>Newest First</option>
                    <option value="oldest" @if(request('sort_by') == 'oldest') selected @endif>Oldest First</option>
                </select>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('management.logs.index') }}" class="text-sm ... mr-4">Reset</a>
                <x-primary-button>
                    {{ __('Apply') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</x-app-layout>
