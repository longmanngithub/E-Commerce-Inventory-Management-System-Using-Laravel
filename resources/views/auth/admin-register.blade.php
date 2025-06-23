<x-guest-layout>
    <div>
        <a href="/">
                <span class="flex items-center">
                        <svg version="1.1" id="Layer_1" class="h-16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 512 512" xml:space="preserve">
                            <polygon style="fill:#83C9FF;" points="512,178.087 512,100.174 478.609,100.174 478.609,33.391 445.217,33.391 445.217,0 66.783,0
                                66.783,33.391 33.391,33.391 33.391,100.174 0,100.174 0,178.087 33.391,178.087 33.391,445.217 0,445.217 0,512 512,512
                                512,445.217 478.609,445.217 478.609,178.087 "/>
                        <polygon style="fill:#FB0023;" points="478.609,100.174 478.609,33.391 445.217,33.391 445.217,0 66.783,0 66.783,33.391
                                33.391,33.391 33.391,100.174 0,100.174 0,178.087 33.391,178.087 33.391,211.478 89.043,211.478 89.043,178.087 122.435,178.087
                                122.435,211.478 189.217,211.478 189.217,178.087 222.609,178.087 222.609,211.478 289.391,211.478 289.391,178.087
                                322.783,178.087 322.783,211.478 389.565,211.478 389.565,178.087 422.957,178.087 422.957,211.478 478.609,211.478
                                478.609,178.087 512,178.087 512,100.174 "/>
                        <g>
                            <rect x="122.435" style="fill:#FFFFFF;" width="66.783" height="211.478"/>
                            <rect x="322.783" style="fill:#FFFFFF;" width="66.783" height="211.478"/>
                        </g>
                        <g>
                            <rect x="100.174" y="244.87" style="fill:#00479B;" width="100.174" height="200.348"/>
                            <rect x="233.739" y="244.87" style="fill:#00479B;" width="178.087" height="122.435"/>
                        </g>
                        <polygon style="fill:#787680;" points="478.609,445.217 478.609,411.826 33.391,411.826 33.391,445.217 0,445.217 0,512 512,512
                                512,445.217 "/>
                        <rect y="100.174" width="33.391" height="77.913"/>
                        <rect x="89.043" y="133.565" width="33.391" height="44.522"/>
                        <rect x="122.435" y="178.087" width="66.783" height="33.391"/>
                        <rect x="289.391" y="133.565" width="33.391" height="44.522"/>
                        <rect x="189.217" y="133.565" width="33.391" height="44.522"/>
                        <rect x="322.783" y="178.087" width="66.783" height="33.391"/>
                        <rect x="222.609" y="178.087" width="66.783" height="33.391"/>
                        <rect x="389.565" y="133.565" width="33.391" height="44.522"/>
                        <rect x="478.609" y="100.174" width="33.391" height="77.913"/>
                        <rect x="33.391" y="33.391" width="33.391" height="66.783"/>
                        <rect x="445.217" y="33.391" width="33.391" height="66.783"/>
                        <rect x="66.783" width="378.435" height="33.391"/>
                        <path d="M478.609,178.087h-55.652v33.391h22.261v200.348H66.783V211.478h22.261v-33.391H33.391v267.13h445.217V178.087z"/>
                        <polygon points="33.391,478.609 33.391,445.217 0,445.217 0,512 512,512 512,445.217 478.609,445.217 478.609,478.609 "/>
                            </svg>
                        <h1 class="dark:text-gray-200 text-4xl ms-5 font-black">Logi-Flow</h1>
                </span>
        </a>

        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-8 mb-2">
            Create an Account
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Let’s create your new account
        </p>
    </div>


    <form method="POST" action="{{ route('register.attempt') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" :value="__('Name')" />
            <x-text-input id="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition duration-200" type="text" name="name" :value="old('name')" placeholder="Enter your user name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" :value="__('Email')" />
            <x-text-input id="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition duration-200" type="email" name="email" :value="old('email')" placeholder="Enter your email address" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" :value="__('Password')" />

            <x-text-input id="password" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition duration-200"
                          type="password"
                          name="password"
                          placeholder="Enter your password"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition duration-200"
                          type="password"
                          name="password_confirmation" placeholder="Enter your confirmation password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 justify-end flex">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <div class="flex">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-xl transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Continue
            </button>
        </div>
    </form>
</x-guest-layout>
