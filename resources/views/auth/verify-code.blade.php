<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-2xl font-bold">Enter Verification Code</h1>
        <p class="text-sm text-gray-600">
            {{-- We use session('email') because we were redirected here from the previous step --}}
            We have sent a verification code to {{ session('email') }}.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.verify.code') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email ?? session('email') }}">

        <div class="flex justify-center space-x-2" x-data="{
            code: Array(6).fill(''),
            handleInput(index, event) {
                // Only allow numbers
                if (! /^[0-9]$/.test(event.target.value)) {
                    event.target.value = '';
                    return;
                }
                // Move to next input
                if (event.target.value && index < 6) {
                    $refs['code-input-' + (index + 1)].focus();
                }
                // Update the hidden combined code field
                $refs.combined_code.value = this.code.join('');
            },
            handlePaste(event) {
                let paste = (event.clipboardData || window.clipboardData).getData('text').slice(0, 6);
                paste.split('').forEach((char, i) => {
                    this.code[i] = char;
                });
                 $refs.combined_code.value = this.code.join('');
            }
        }" @paste.prevent="handlePaste">
            {{-- This hidden input will hold the combined 6-digit code --}}
            <input type="hidden" name="code" x-ref="combined_code">

            @for ($i = 0; $i < 6; $i++)
                <input  type="text"
                        x-ref="code-input-{{ $i + 1 }}"
                        class="w-12 h-12 text-center text-lg font-semibold border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        maxlength="1"
                        x-model="code[{{ $i }}]"
                        @input.debounce="handleInput({{ $i + 1 }}, $event)">
            @endfor
        </div>
        <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />


        <div class="flex items-center justify-center mt-6">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
