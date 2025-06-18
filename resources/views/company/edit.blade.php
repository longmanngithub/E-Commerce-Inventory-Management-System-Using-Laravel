<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- NEW: Subscription Status Section --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="flex items-start space-x-6 border-b pb-6 mb-6">
                        {{-- Logo --}}
                        <div class="flex-shrink-0">
                            @if($company['imageUrl'])
                                <img src="{{ $company['imageUrl'] }}" alt="{{ $company['name'] }}" class="h-24 w-24 rounded-lg object-contain bg-gray-100 p-1">
                            @else
                                <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-500">{{ substr($company['name'], 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- Details --}}
                        <div class="flex-grow space-y-1">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $company['name'] }}</h2>
                            <p class="text-sm text-gray-500">Total users: {{ $company['totalUsers'] }}</p>
                            @if($company['subscription'])
                                <p class="text-sm text-gray-500">
                                    Status: <span class="font-medium {{ $company['subscription']['status'] === 'Paid' ? 'text-green-600' : 'text-red-600' }}">{{ $company['subscription']['status'] }}</span>
                                </p>
                                <p class="text-sm text-gray-500">Subscription Plan: <span class="font-medium text-gray-800">{{ $company['subscription']['plan'] }}</span></p>
                                <p class="text-sm text-gray-500">Next billing cycle: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($company['subscription']['nextBillingCycle'])->format('F d, Y') }}</span></p>
                            @endif
                        </div>
                        {{-- Change Plan Button --}}
                        @if(Auth::user()->is_owner)
                        <div>
                            <a href="{{ route('subscription.plans') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 whitespace-nowrap">Change Plan</a>
                        </div>
                        @endif
                    </header>

                    {{-- Check if company data was loaded successfully from the API --}}
                    @if(!empty($company))
                        {{-- Update Company Information Form --}}
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Company Information') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Update your company's profile information and email address.
                                    </p>
                                </header>

                                <form method="POST" action="{{ route('management.company.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    {{-- Company Name --}}
                                    <div>
                                        <x-input-label for="company_name" :value="__('Company Name')" />
                                        <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $company['name'])" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                                    </div>

                                    {{-- Company Email --}}
                                    <div>
                                        <x-input-label for="company_email" :value="__('Company Email')" />
                                        <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" :value="old('company_email', $company['email'])" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_email')" />
                                    </div>

                                    {{-- Company Website --}}
                                    <div>
                                        <x-input-label for="company_website" :value="__('Company Website')" />
                                        <x-text-input id="company_website" name="company_website" type="text" class="mt-1 block w-full" :value="old('company_website', $company['website'])" />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_website')" />
                                    </div>

                                    {{-- Company Telephone --}}
                                    <div>
                                        <x-input-label for="company_telephone" :value="__('Company Telephone')" />
                                        <x-text-input id="company_telephone" name="company_telephone" type="text" class="mt-1 block w-full" :value="old('company_telephone', $company['telephone'])" />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_telephone')" />
                                    </div>

                                    {{-- Company Address --}}
                                    <div>
                                        <x-input-label for="company_address" :value="__('Company Address')" />
                                        <textarea id="company_address" name="company_address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('company_address', $company['address']) }}</textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                                    </div>

                                    {{-- Company Logo Upload --}}
                                    <div>
                                        <x-input-label for="company_image" :value="__('Company Logo')" />
                                        @if($company['imageUrl'])
                                            <img src="{{ $company['imageUrl'] }}" class="h-16 w-16 my-2 rounded-md object-contain">
                                        @endif
                                        <input type="file" name="company_image" id="company_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-gray-100 hover:file:bg-gray-200"/>
                                        <x-input-error class="mt-2" :messages="$errors->get('company_image')" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                                        @if (session('status') === 'profile-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                        @endif
                                    </div>
                                </form>
                            </section>
                        </div>
                    @else
                        <p>Could not load company data.</p>
                    @endif

                </section>
            </div>




                {{-- Deactivate Company "Danger Zone" --}}
            @if(Auth::user()->is_owner)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-red-600">{{ __('Deactivate Company') }}</h2>
                            <p class="mt-1 text-sm text-gray-600">Deactivating your account will disable access for all users. Your data will be preserved.</p>
                        </header>

                        {{-- This button now opens the modal --}}
                        <x-danger-button class="mt-6" x-data=""
                                         x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-company-deactivation' })">
                            {{ __('Deactivate Company') }}
                        </x-danger-button>
                    </section>
                </div>
            @endif

                {{-- This is the new confirmation modal --}}
                <x-modal name="confirm-company-deactivation" :show="$errors->userDeletion->isNotEmpty()" focusable>

                    <form method="post" action="{{ route('management.company.deactivate') }}" class="p-6">
                        @csrf
                        <h2 class="text-lg font-medium text-gray-900">
                            Are you sure you want to deactivate your company?
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Please enter your password to confirm you would like to deactivate your company account.
                        </p>

                        <div class="mt-6">
                            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                            <x-text-input
                                id="password"
                                name="password"
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="{{ __('Password') }}"
                            />
                            {{-- This will display any password-related errors returned from the API --}}
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
        </div>
    </div>
</x-app-layout>
