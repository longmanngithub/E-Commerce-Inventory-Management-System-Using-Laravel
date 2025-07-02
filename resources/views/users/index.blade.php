<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('User Management') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage all user permissions</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="lg:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('User Management') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage all user permissions</h4>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl transition-colors">

                {{-- Invite Users Button --}}
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-end space-x-4 mb-8">
                        @if(Auth::guard('company_admin')->check())
                            <button @click="$dispatch('open-modal', { name: 'invite-user' })"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" viewBox="0 0 16 16">
                                    <path fill="currentColor" d="M8 15c-3.86 0-7-3.14-7-7s3.14-7 7-7s7 3.14 7 7s-3.14 7-7 7M8 2C4.69 2 2 4.69 2 8s2.69 6 6 6s6-2.69 6-6s-2.69-6-6-6" />
                                    <path fill="currentColor" d="M8 11.5c-.28 0-.5-.22-.5-.5V5c0-.28.22-.5.5-.5s.5.22.5.5v6c0 .28-.22.5-.5.5" />
                                    <path fill="currentColor" d="M11 8.5H5c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h6c.28 0 .5.22.5.5s-.22.5-.5.5" />
                                </svg>
                                Invite User
                            </button>
                        @endif
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-tl-lg">Image</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Permissions</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-tr-lg">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                        @if($user['imageUrl'])
                                            <div class="h-16 w-16">
                                                <img src="{{ $user['imageUrl'] }}" alt="{{ $user['name'] }}"
                                                     class="h-full w-full object-contain rounded-full">
                                            </div>
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-full border-2 border-gray-200 dark:border-gray-600">
                                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $user['name'] }}</td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $user['email'] }}</td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $user['role'] }}</td>
                                    <td class="px-6 py-4">
                                        {{-- Display permissions for staff --}}
                                        @if($user['type'] === 'staff' && !empty($user['permissions']))
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($user['permissions'] as $permission)
                                                    @php
                                                        // Add color coding for different permissions
                                                        $permissionColor = match($permission) {
                                                            'create_product' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                            'update_product' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                            'delete_product' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                        };
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $permissionColor }}">
                                                                {{ ucwords(str_replace('_', ' ', $permission)) }}
                                                            </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end items-center space-x-3">
                                            {{-- Only show Edit button for Staff --}}
                                            @if($user['type'] === 'staff')
                                                <button class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                        x-data
                                                        @click.prevent="$dispatch('open-modal', { name: 'edit-user', user: {{ json_encode($user) }} })">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                            @endif

                                            {{-- Updated Delete button to open modal --}}
                                            @if($user['role'] !== 'Company Owner' && ($user['id'] !== Auth::id()))
                                                <button type="button"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-sm font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                        x-data
                                                        @click.prevent="$dispatch('open-modal', { name: 'delete-user', user: {{ json_encode($user) }} })">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">No users found</p>
                                            <p class="text-sm mt-1">Start by inviting your first user to the system.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($users as $user)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-4">
                                    {{-- User Info Section --}}
                                    <div class="flex items-start space-x-4 mb-4">
                                        {{-- Avatar --}}
                                        <div class="flex-shrink-0">
                                            @if($user['imageUrl'])
                                                <img src="{{ $user['imageUrl'] }}" alt="{{ $user['name'] }}"
                                                     class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-full border-2 border-gray-200 dark:border-gray-600">
                                                    <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- User Details --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $user['name'] }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $user['email'] }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Role: {{ $user['role'] }}</p>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex space-x-2">
                                            {{-- Edit Button for Staff --}}
                                            @if($user['type'] === 'staff')
                                                <button class="p-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                        x-data
                                                        @click.prevent="$dispatch('open-modal', { name: 'edit-user', user: {{ json_encode($user) }} })"
                                                        title="Edit User">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Delete Button --}}
                                            @if($user['role'] !== 'Company Owner' && ($user['id'] !== Auth::id()))
                                                <button class="p-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                        x-data
                                                        @click.prevent="$dispatch('open-modal', { name: 'delete-user', user: {{ json_encode($user) }} })"
                                                        title="Delete User">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Permissions Section --}}
                                    @if($user['type'] === 'staff' && !empty($user['permissions']))
                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permission:</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($user['permissions'] as $permission)
                                                    @php
                                                        $permissionColor = match($permission) {
                                                            'create_product' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                            'update_product' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                            'delete_product' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                                        };
                                                    @endphp
                                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $permissionColor }}">
                                                        {{ ucwords(str_replace('_', ' ', $permission)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No users found</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Start by inviting your first user to the system.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Invite User Modal - Sliding from Right --}}
@include('users/create')

{{-- Edit User Modal - Enhanced with Dark Mode and Animations --}}
<div x-data="{
    show: false,
    user: null,
    permissions: {},
    resetForm() {
        this.permissions.create_product = this.user?.permissions?.includes('create_product') || false;
        this.permissions.update_product = this.user?.permissions?.includes('update_product') || false;
        this.permissions.delete_product = this.user?.permissions?.includes('delete_product') || false;
    }
}"
     @open-modal.window="if ($event.detail.name === 'edit-user') {
         show = true;
         user = $event.detail.user;
         resetForm();
     }"
     @close-modal.window="if ($event.detail.name === 'edit-user') show = false"
     @keydown.escape.window="show = false"
     x-show="show"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black bg-opacity-75 dark:bg-black dark:bg-opacity-80 transition-all duration-300"
         @click="show = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 shadow-2xl transition-all duration-300"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h2>
                <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200 hover:scale-110 transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <template x-if="user">
                <form :action="'/management/users/staff/' + user.id" method="POST" class="px-6 py-6">
                    @csrf
                    @method('PUT')

                    {{-- Permission Section --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Permission</h3>

                        <div class="space-y-4">
                            {{-- Create Product Toggle --}}
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                <span class="text-gray-900 dark:text-white font-medium">Create product</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="create_product"
                                           x-model="permissions.create_product"
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all duration-300 peer-checked:bg-green-500 dark:peer-checked:bg-green-600 hover:scale-105 transform"></div>
                                </label>
                            </div>

                            {{-- Update Product Toggle --}}
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                <span class="text-gray-900 dark:text-white font-medium">Update product</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="update_product"
                                           x-model="permissions.update_product"
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all duration-300 peer-checked:bg-green-500 dark:peer-checked:bg-green-600 hover:scale-105 transform"></div>
                                </label>
                            </div>

                            {{-- Delete Product Toggle --}}
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                <span class="text-gray-900 dark:text-white font-medium">Delete product</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="delete_product"
                                           x-model="permissions.delete_product"
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all duration-300 peer-checked:bg-green-500 dark:peer-checked:bg-green-600 hover:scale-105 transform"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="button"
                                @click="resetForm()"
                                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 hover:scale-105 transform focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Reset
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-3 bg-blue-600 dark:bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-200 hover:scale-105 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Apply
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>

{{-- Delete User Modal - Enhanced with Dark Mode and Animations --}}
<div x-data="{
    show: false,
    user: null
}"
     @open-modal.window="if ($event.detail.name === 'delete-user') {
         show = true;
         user = $event.detail.user;
     }"
     @close-modal.window="if ($event.detail.name === 'delete-user') show = false"
     @keydown.escape.window="show = false"
     x-show="show"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 transition-all duration-300"
         @click="show = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all duration-300"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            {{-- Content --}}
            <div class="p-6 text-center">
                {{-- Animated Warning Icon --}}
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4 animate-pulse">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Delete account?</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-1">Are you sure you want to delete this account? This action cannot be undone.</p>
            </div>

            {{-- Action Buttons --}}
            <template x-if="user">
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            @click="show = false"
                            class="w-full px-4 py-4 text-blue-600 dark:text-blue-400 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 border-b border-gray-200 dark:border-gray-700 hover:scale-105 transform">
                        Cancel
                    </button>
                    <form :action="user.type === 'admin' ? '/management/users/admin/' + user.id : '/management/users/staff/' + user.id"
                          method="POST"
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-4 text-red-600 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-900 hover:bg-opacity-20 transition-all duration-200 hover:scale-105 transform focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-inset">
                            Delete
                        </button>
                    </form>
                </div>
            </template>
        </div>
    </div>
</div>

