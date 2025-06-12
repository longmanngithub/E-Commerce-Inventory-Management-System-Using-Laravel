<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Update Profile Information Form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Payment Method section, only shown for the Company Owner --}}
            @if(Auth::user()->is_owner)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Payment Method') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Update your payment method.
                                </p>
                            </header>

                            <div class="mt-6 space-y-4">
                                <div class="p-4 border rounded-md flex items-center justify-between">
                                    <div class="flex items-center">
                                        {{-- Placeholder for a card logo --}}
                                        <svg class="h-8 w-auto" ...> ... </svg>
                                        <div class="ms-4">
                                            <div class="text-sm font-medium">Visa ending in 1234</div>
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
            @if(!$user->is_owner)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
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
                <form method="post" action="{{ Auth::guard('platform_owner')->check() ? route('platform_owner.profile.destroy') : route('admin.profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-gray-900">
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
</x-app-layout>
