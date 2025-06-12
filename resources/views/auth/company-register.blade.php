<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-2xl font-bold">Register Your Company</h1>
        <p class="text-sm text-gray-600">Let us know about your company</p>
    </div>

    @php
        // When the first form submits here, we'll have the user data in the request.
        // We are using old() to repopulate the fields in case of a validation error on this page.
        $userName = old('user_name', request()->name);
        $userEmail = old('user_email', request()->email);
        $userPassword = old('user_password', request()->password);
        $userPasswordConfirmation = old('user_password_confirmation', request()->password_confirmation);
    @endphp

    <form method="POST" action="{{ route('register.company.store') }}">
        @csrf

        {{-- Hidden fields to carry over the user data --}}
        <input type="hidden" name="user_name" value="{{ $userName }}">
        <input type="hidden" name="user_email" value="{{ $userEmail }}">
        <input type="hidden" name="user_password" value="{{ $userPassword }}">
        <input type="hidden" name="user_password_confirmation" value="{{ $userPasswordConfirmation }}">


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
