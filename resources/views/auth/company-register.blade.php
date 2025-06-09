<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-2xl font-bold">Register Your Company</h1>
        <p class="text-sm text-gray-600">Let us know about your company</p>
    </div>

    <form method="POST" action="{{ route('register.company.store') }}">
        @csrf

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="company_email" :value="__('Company Email')" />
            <x-text-input id="company_email" class="block mt-1 w-full" type="email" name="company_email" :value="old('company_email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="company_address" :value="__('Company Address')" />
            <x-text-input id="company_address" class="block mt-1 w-full" type="text" name="company_address" :value="old('company_address')" required autocomplete="street-address" />
            <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Sign Up') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
