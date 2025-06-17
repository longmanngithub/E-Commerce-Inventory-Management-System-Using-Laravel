<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-2xl font-bold">Set Your Account Password</h1>
        <p class="text-sm text-gray-600">You've been invited to join the team. Create a password to activate your account.</p>
    </div>

    <form method="POST" action="{{ route('invitation.store_password') }}">
        @csrf

        {{-- Hidden fields to pass the token and email to the next request --}}
        <input type="hidden" name="token" value="{{ $invitationData['token'] }}">
        <input type="hidden" name="email" value="{{ $invitationData['email'] }}">

        {{-- Email Address (disabled for user) --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-100"
                          type="email"
                          :value="$invitationData['email']"
                          disabled />
        </div>

        {{-- Password Input --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password Input --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Set Password and Activate Account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
