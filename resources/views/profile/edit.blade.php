<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Account Settings') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage account</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            {{-- Profile --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                {{-- This form is ONLY for updating the profile photo --}}
                <form id="photo-upload-form" method="post" action="{{ route('admin.profile.updatePhoto') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="flex items-center">
                        {{-- Profile Picture Display --}}
                        <div class="flex-shrink-0">
                            <img class="h-20 w-20 rounded-full object-cover" src="{{ Auth::user()->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $user['admin_name'] ?? $user['staff_name'] }}">
                        </div>

                        {{-- Name and Email (Display Only) --}}
                        <div class="ms-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $user['admin_name'] ?? $user['staff_name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $user['admin_email'] ?? $user['staff_email'] }}</p>

                            {{-- This styled label triggers the hidden file input --}}
                            <div class="mt-3">
                                <label for="photo-input" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-500 cursor-pointer">
                                    <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" /><path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" /></svg>
                                    <span>Upload New Photo</span>
                                </label>
                                <input type="file" id="photo-input" name="profile_picture" class="hidden">
                                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            {{-- Update Profile Information Form --}}
            <div class="grid grid-cols-2 gap-6 my-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="w-full">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password Form --}}
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="w-full">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Payment Method section, only shown for the Company Owner --}}
            @if(isset($user['is_owner']) && $user['is_owner'])
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ __('Payment Method') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Update your payment method.
                                </p>
                            </header>

                            <div class="mt-6 space-y-4" x-data="{ cardType: 'visa' }">
                                <div class="p-4 border rounded-md flex items-center justify-between">
                                    <div class="flex items-center">
                                        {{-- Placeholder for a card logo --}}
                                        <img x-show="cardType === 'visa'" src="{{ asset('assets/images/visa.svg') }}" alt="Visa" class="h-8 w-auto">
                                        <div class="ms-4">
                                            <div class="text-sm font-medium dark:text-white">Visa ending in 1234</div>
                                            <div class="text-sm text-gray-500">Expiry 12 / 25</div>
                                        </div>
                                    </div>
                                    <button class="text-sm font-medium text-gray-500 hover:text-gray-700">Remove</button>
                                </div>
                                <p class="text-sm text-gray-600">Billing Address: 123 Main Street, San Francisco, CA 94102</p>
                            </div>
                        </section>
                    </div>
                </div>
            @endif

            {{-- Delete Account "Danger Zone" --}}
            @if(!isset($user['is_owner']) || !$user['is_owner'])
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-red-600">
                                {{ __('Delete Account') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Once your account is deleted, all of your resources and data will be permanently deleted.
                            </p>
                        </header>

                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        >{{ __('Delete Account') }}</x-danger-button>
                    </section>
                </div>
            </div>

            {{-- This is the confirmation modal that will pop up --}}
            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('admin.profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Are you sure you want to delete your account?
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-danger-button class="ms-3">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const photoInput = document.getElementById('photo-input');
                const photoForm = document.getElementById('photo-upload-form');

                if (photoInput && photoForm) {
                    // When a new file is selected, automatically submit the form.
                    photoInput.addEventListener('change', function () {
                        if (this.files.length > 0) {
                            photoForm.submit();
                        }
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>
