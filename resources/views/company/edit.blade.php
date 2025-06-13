<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- COMPANY SUMMARY HEADER --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        @if ($company->company_image)
                            <img src="{{ asset('storage/' . $company->company_image) }}" alt="{{ $company->company_name }}" class="h-20 w-20 rounded-md object-contain">
                        @endif
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $company->company_name }}</h2>
                            <p class="mt-1 text-sm text-gray-500">Total users: {{ $totalUsers }}</p>
                            {{-- Subscription details require a subscription record --}}
                            @if ($company->subscription)
                                <p class="mt-2 text-sm text-gray-600">
                                    Status: <span class="font-semibold {{ $company->subscription->is_paid ? 'text-green-600' : 'text-red-600' }}">{{ $company->subscription->is_paid ? 'Paid' : 'Unpaid' }}</span>
                                </p>
                                <p class="text-sm text-gray-600">Subscription Plan: <span class="font-semibold">{{ $company->subscription->subscription_tier }}</span></p>
                                <p class="text-sm text-gray-600">Next billing cycle: <span class="font-semibold">{{ \Carbon\Carbon::parse($company->subscription->renew_date)->format('F d, Y') }}</span></p>
                            @else
                                <p class="mt-2 text-sm text-gray-600">No active subscription.</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('subscription.plans') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Change Plan
                        </a>
                    </div>
                </div>
            </div>


            {{-- Form Section --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ $company->company_name }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your company's profile information and email address.
                        </p>
                    </header>

                    <form method="POST" action="{{ route('management.company.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        {{-- Company Name --}}
                        <div>
                            <x-input-label for="company_name" :value="__('Company Name')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $company->company_name)" readonly />
                            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                        </div>

                        {{-- Company Email --}}
                        <div>
                            <x-input-label for="company_email" :value="__('Company Email')" />
                            <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" :value="old('company_email', $company->company_email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('company_email')" />
                        </div>

                        {{-- Company Website & Telephone --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="company_website" :value="__('Company Website')" />
                                <x-text-input id="company_website" name="company_website" type="text" class="mt-1 block w-full" :value="old('company_website', $company->company_website)" />
                                <x-input-error class="mt-2" :messages="$errors->get('company_website')" />
                            </div>
                            <div>
                                <x-input-label for="company_telephone" :value="__('Company Telephone')" />
                                <x-text-input id="company_telephone" name="company_telephone" type="text" class="mt-1 block w-full" :value="old('company_telephone', $company->company_telephone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('company_telephone')" />
                            </div>
                        </div>

                        {{-- Company Address --}}
                        <div>
                            <x-input-label for="company_address" :value="__('Company Address')" />
                            <textarea id="company_address" name="company_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('company_address', $company->company_address) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                        </div>

                        {{-- Company Logo --}}
                        <div>
                            <x-input-label for="company_image" :value="__('Company Logo')" />
                            @if ($company->company_image)
                                <img src="{{ asset('storage/' . $company->company_image) }}" alt="{{ $company->company_name }}" class="h-20 w-auto rounded-md object-contain my-2">
                            @endif
                            <input id="company_image" name="company_image" type="file" class="block w-full">
                            <x-input-error class="mt-2" :messages="$errors->get('company_image')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            @if (session('status') === 'Company information updated successfully!')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone section --}}
            @can('deactivate-company')
                <section class="space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-red-600">
                            {{ __('Deactivate Company') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Deactivating your account will disable access for all users. Your data will be preserved.
                        </p>
                    </header>

                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-company-deactivation')"
                    >{{ __('Deactivate Company') }}</x-danger-button>

                    <x-modal name="confirm-company-deactivation" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('management.company.deactivate') }}" class="p-6">
                            @csrf
                            {{-- No @method('delete') needed anymore --}}

                            <h2 class="text-lg font-medium text-gray-900">
                                Are you sure you want to deactivate your company?
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Please enter your password to confirm you would like to deactivate your company account.
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
                                    {{ __('Deactivate Company') }}
                                </x-danger-button>
                            </div>
                        </form>
                    </x-modal>
                </section>
            @endcan

        </div>
    </div>

</x-app-layout>
