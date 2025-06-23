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

    <h1 class="dark:text-gray-200 text-4xl font-black">Forgot Password</h1>
    <div class="mb-8 mt-3 text-lg text-gray-600 dark:text-gray-400">
        {{ __('Enter your registered email address. we’ll send you a code to reset your password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="owner_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input id="owner_email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition duration-200" type="email" name="owner_email" :value="old('email')"  placeholder="Enter your email address" required autofocus />
            <x-input-error :messages="$errors->get('owner_email')" class="mt-2" />
        </div>

        <div class="flex mt-12">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-xl transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Send Code
            </button>
        </div>
    </form>
</x-guest-layout>

