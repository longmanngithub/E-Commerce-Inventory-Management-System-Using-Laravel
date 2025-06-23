<div x-data="{
    show: false,
    role: 'staff',
    resetForm() {
        this.role = 'staff';
        const form = document.getElementById('invite-form');
        if (form) form.reset();
    },
    closeModal() {
        this.show = false;
        setTimeout(() => {
            this.resetForm();
        }, 300);
    }
}"
     @open-modal.window="if ($event.detail.name === 'invite-user') { show = true; }"
     @close-modal.window="if ($event.detail.name === 'invite-user') { closeModal(); }"
     @keydown.escape.window="if (show) { closeModal(); }"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-hidden">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70"
         @click="closeModal()"
         x-show="show"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    {{-- Sliding Panel --}}
    <div class="absolute inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16"
         x-show="show"
         x-transition:enter="transform transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        <div class="w-screen max-w-md">
            <div class="flex h-full flex-col bg-white dark:bg-gray-900 shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add New User</h2>
                    </div>
                    <button @click="closeModal()"
                            type="button"
                            class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200 hover:scale-110 transform focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-lg p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form Content --}}
                <div class="flex-1 overflow-y-auto px-6 py-6">
                    <form method="POST" action="{{ route('management.users.store') }}" id="invite-form" class="space-y-6">
                        @csrf

                        {{-- User Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User Name <span class="text-red-500">*</span>
                            </label>
                            <input id="name"
                                   type="text"
                                   name="name"
                                   :value="old('name')"
                                   required
                                   autofocus
                                   placeholder="Enter user name"
                                   class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Role Dropdown --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="role"
                                        id="role"
                                        x-model="role"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none bg-white dark:bg-gray-800">
                                    <option value="">Choose role</option>
                                    <option value="staff">Staff</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        {{-- Email Address --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   :value="old('email')"
                                   required
                                   placeholder="Enter email address"
                                   class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Permission checkboxes - Only for Staff --}}
                        <div x-show="role === 'staff'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Permission <span class="text-red-500">*</span>
                                </label>

                                @php $allPermissions = ['create_product', 'update_product', 'delete_product']; @endphp

                                <div class="space-y-4">
                                    @foreach($allPermissions as $permission)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ ucwords(str_replace('_', ' ', $permission)) }}
                                            </span>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission }}"
                                                       class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-500"></div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Action Buttons --}}
                <div class="flex-shrink-0 px-6 py-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <div class="flex gap-3">
                        <button type="button"
                                @click.stop="closeModal()"
                                class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-medium bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Cancel
                        </button>
                        <button type="submit"
                                form="invite-form"
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 shadow-sm">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
