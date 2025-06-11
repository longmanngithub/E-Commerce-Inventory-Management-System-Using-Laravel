<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invite New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('management.users.store') }}" x-data="{ role: 'staff' }">
                        @csrf

                        {{-- Name --}}
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Role Dropdown --}}
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select name="role" id="role" x-model="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- Permission checkboxes --}}
                        <div x-show="role === 'staff'" class="mt-4 border-t pt-4">
                            <h3 class="text-lg font-medium">Permissions</h3>
                            <p class="text-sm text-gray-600 mb-4">Grant permissions to this staff member.</p>

                            @php $allPermissions = ['create_product', 'update_product', 'delete_product']; @endphp

                            <div class="space-y-2">
                                @foreach($allPermissions as $permission)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="rounded ...">
                                        <span class="ms-2 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $permission)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Send button --}}
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Send Invitation') }}
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
