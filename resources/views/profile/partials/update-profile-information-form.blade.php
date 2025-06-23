<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Personal Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user['admin_name'] ?? $user['staff_name'])" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user['admin_email'] ?? $user['staff_email'])" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Display the user's role --}}
        <div>
            <x-input-label for="role" :value="__('Role')" />

            @php
                // Check for array keys instead of object type
                $roleName = 'Unknown'; // A safe default
                if (isset($user['is_owner']) && $user['is_owner']) {
                    $roleName = 'Company Owner';
                } elseif (isset($user['admin_name'])) {
                    $roleName = 'Admin';
                } elseif (isset($user['staff_name'])) {
                    $roleName = 'Staff';
                }
            @endphp

            <x-text-input id="role" name="role" type="text" class="mt-1 block w-full bg-gray-100" :value="$roleName" disabled />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>

    </form>
</section>
