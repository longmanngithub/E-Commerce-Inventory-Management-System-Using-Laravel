<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ Auth::guard('platform_owner')->check() ? route('platform_owner.profile.update') : route('admin.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->admin_name ?? $user->staff_name ?? $user->owner_name)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->admin_email ?? $user->staff_email ?? $user->owner_email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Display the user's role --}}
        <div>
            <x-input-label for="role" :value="__('Role')" />

            @php
                // Determine the role name based on the user's type
                $roleName = 'Staff'; // Default to Staff
                if ($user instanceof \App\Models\CompanyAdmin && $user->is_owner) {
                    $roleName = 'Company Owner';
                } elseif ($user instanceof \App\Models\CompanyAdmin) {
                    $roleName = 'Admin';
                } elseif ($user instanceof \App\Models\PlatformOwner) {
                    $roleName = 'Platform Owner';
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
