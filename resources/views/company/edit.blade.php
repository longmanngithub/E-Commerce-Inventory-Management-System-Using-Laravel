<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Company Settings') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage company information</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="lg:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Company Settings') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage company information</h4>
            </div>


            {{-- Subscription Status Section --}}
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-2xl">
                <section>
                    <header class="flex items-start space-y-6 border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">

                        <div class="w-full flex flex-col md:flex-row justify-between space-x-0 space-y-6 md:space-x-6 md:space-y-0">
                            {{-- Logo --}}
                            <div class="flex-shrink-0">
                                @if($company['imageUrl'])
                                    <img src="{{ $company['imageUrl'] }}" alt="{{ $company['name'] }}" class="h-24 w-24 rounded-lg object-contain bg-gray-100 dark:bg-gray-700 p-1">
                                @else
                                    <div class="h-24 w-24 rounded-2xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-gray-500 dark:text-gray-400">{{ substr($company['name'], 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-grow space-y-2">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company['name'] }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total users: {{ $company['totalUsers'] }}</p>
                                @if($company['subscription'])
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                        <div class="flex items-center space-x-1">
                                            {{-- Status Icon --}}
                                            @if($company['subscription']['status'] === 'Paid')
                                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            @else
                                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                            @endif
                                            <span class="font-medium {{ $company['subscription']['status'] === 'Paid' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $company['subscription']['status'] }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Subscription Plan: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $company['subscription']['plan'] }}</span></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Next billing cycle: <span class="font-medium text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($company['subscription']['nextBillingCycle'])->format('F d, Y') }}</span></p>
                                @endif

                                {{-- Company Logo Upload Button --}}
                                <div>
                                    <div class="relative inline-block mt-3">
                                        <input type="file" name="company_image" id="company_image" class="sr-only" accept="image/*" onchange="handleFileUpload(this)"/>
                                        <label for="company_image" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg cursor-pointer transition-colors duration-200">
                                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" /><path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" /></svg>
                                            Upload New Photo
                                        </label>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('company_image')" />
                                </div>
                            </div>
                        </div>

                        {{-- Change Plan Button --}}
                        @if(Auth::user()->is_owner)
                            <div class="flex-shrink-0">
                                <a href="{{ route('subscription.plans') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                    Change Plan
                                </a>
                            </div>
                        @endif
                    </header>

                    {{-- Check if company data was loaded successfully from the API --}}
                    @if(!empty($company))
                        {{-- Update Company Information Form --}}
                        <div class="p-1 sm:p-1">
                            <section>
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ __('Company Information') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Update your company's profile information and email address.
                                    </p>
                                </header>

                                <form method="POST" action="{{ route('management.company.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                                    </div>

                                    {{-- Company Address --}}
                                    <div>
                                        <x-input-label for="company_address" :value="__('Company Address')" />
                                        <textarea id="company_address" name="company_address" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">{{ old('company_address', $company['address']) }}</textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                                        @if (session('status') === 'profile-updated')
                                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-blue-400">{{ __('Saved.') }}</p>
                                        @endif
                                    </div>
                                </form>
                            </section>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">Could not load company data.</p>
                    @endif

                </section>
            </div>

            {{-- Deactivate Company "Danger Zone" --}}
            @if(Auth::user()->is_owner)
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-2xl mt-8">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-red-600 dark:text-red-400">{{ __('Deactivate Company') }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Deactivating your account will disable access for all users. Your data will be preserved.</p>
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

                <form method="post" action="{{ route('management.company.deactivate') }}" class="p-6 bg-white dark:bg-gray-800">
                    @csrf
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Are you sure you want to deactivate your company?
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
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

    <script>
        function handleFileUpload(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                console.log('File selected:', file.name);
            }
        }
    </script>
</x-app-layout>
