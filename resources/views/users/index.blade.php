<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            @if(Auth::guard('company_admin')->check())
                <a href="{{ route('management.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Invite User
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="border-b">
                                <td class="px-6 py-4">{{ $user['name'] }}</td>
                                <td class="px-6 py-4">{{ $user['email'] }}</td>
                                <td class="px-6 py-4">{{ $user['role'] }}</td>
                                <td class="px-6 py-4">
                                    {{-- Display permissions for staff --}}
                                    @if($user['type'] === 'staff' && !empty($user['permissions']))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user['permissions'] as $permission)
                                                @php
                                                    // THE FIX: Add color coding for different permissions
                                                    $permissionColor = match($permission) {
                                                        'create_product' => 'bg-blue-100 text-blue-800',
                                                        'update_product' => 'bg-green-100 text-green-800',
                                                        'delete_product' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $permissionColor }}">
                                                            {{ ucwords(str_replace('_', ' ', $permission)) }}
                                                        </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        {{-- Only show Edit button for Staff --}}
                                        @if($user['type'] === 'staff')
                                            <button class="text-indigo-600 hover:text-indigo-900"
                                                    x-data
                                                    @click.prevent="$dispatch('open-modal', { name: 'edit-user', user: {{ json_encode($user) }} })">
                                                Edit
                                            </button>
                                        @endif

                                        {{-- Use the correct delete route based on user type --}}
                                        @if($user['role'] !== 'Company Owner' && ($user['id'] !== Auth::id()))
                                        <form action="{{ $user['type'] === 'admin' ? route('management.users.destroy.admin', $user['id']) : route('management.users.destroy.staff', $user['id']) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<x-modal name="edit-user" focusable>
    {{-- We use Alpine.js to manage the form data --}}
    <div x-data="{ user: null, permissions: {} }"
         @open-modal.window="if ($event.detail.name === 'edit-user') {
             user = $event.detail.user;
             permissions.create_product = user.permissions.includes('create_product');
             permissions.update_product = user.permissions.includes('update_product');
             permissions.delete_product = user.permissions.includes('delete_product');
         }">

        <template x-if="user">
            <form :action="'/management/users/staff/' + user.id" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-900" x-text="'Edit Permissions for ' + user.name"></h2>
                <p class="mt-1 text-sm text-gray-600">You can only edit permissions for Staff members.</p>

                <div class="mt-6 space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="create_product" x-model="permissions.create_product">
                        <span class="ms-2 text-sm text-gray-600">Create Product</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="update_product" x-model="permissions.update_product">
                        <span class="ms-2 text-sm text-gray-600">Update Product</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="delete_product" x-model="permissions.delete_product">
                        <span class="ms-2 text-sm text-gray-600">Delete Product</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-primary-button class="ms-3">Save Permissions</x-primary-button>
                </div>
            </form>
        </template>
    </div>
</x-modal>
