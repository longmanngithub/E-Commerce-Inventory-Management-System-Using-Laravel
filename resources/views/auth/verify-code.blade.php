<x-guest-layout>
    <style>
        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 40px;
            transition: color 0.2s ease;
        }

        .back-button:hover {
            color: #1f2937;
        }

        .back-arrow {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }
    </style>


    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back-button dark:text-white">
        <svg class="dark:text-white back-arrow" viewBox="0 0 24 24">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Back
    </a>

    <h1 class="dark:text-gray-200 text-4xl font-black">Enter Verification Code</h1>
    <div class="mb-8 mt-3 text-lg text-gray-600 dark:text-gray-400">
        {{ __('We have sent a verification code to') }} {{ session('email') }}{{ __('.') }}
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
                        class="w-full h-full p-6 text-center text-2xl dark:bg-gray-900 dark:text-white font-semibold border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-lg shadow-sm"
                        maxlength="1"
                        x-model="code[{{ $i }}]"
                        @input.debounce="handleInput({{ $i + 1 }}, $event)">
            @endfor
        </div>
        <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />


        <div class="flex mt-12">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-xl transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Verify
            </button>
        </div>
    </form>
</x-guest-layout>
