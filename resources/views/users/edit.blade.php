<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->admin_name ?? $user->staff_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Note: The user ID could be admin_id or staff_id --}}
                    <form method="POST" action="{{ route('admin.users.update', $user->admin_id ?? $user->staff_id) }}">
                        @csrf
                        @method('PUT')

                        {{-- We only show permissions for staff members --}}
                        @if($user instanceof \App\Models\CompanyStaff)
                            <div>
                                <h3 class="text-lg font-medium">Permissions</h3>
                                <p class="text-sm text-gray-600 mb-4">Grant permissions to this staff member.</p>

                                @php
                                    // The list of all possible permissions
                                    $allPermissions = ['create_product', 'update_product', 'delete_product'];
                                    // The permissions this user currently has (defaults to empty array if null)
                                    $userPermissions = $user->permissions ?? [];
                                @endphp

                                <div class="space-y-2">
                                    @foreach($allPermissions as $permission)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                                   @if(in_array($permission, $userPermissions)) checked @endif
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                            <span class="ms-2 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $permission)) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="text-gray-700">Permission settings are only available for staff members.</p>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
