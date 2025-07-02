<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Account Settings') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage account</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="lg:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Account Settings') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">Manage account</h4>
            </div>

            {{-- Profile --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-2xl">
                {{-- This form is ONLY for updating the profile photo --}}
                <form id="photo-upload-form" method="post" action="{{ route('owner.profile.updatePhoto') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="flex items-center">
                        {{-- Profile Picture Display --}}
                        @if(Auth::user()->image_url)
                            <div class="flex-shrink-0">
                                <img class="h-20 w-20 rounded-full object-cover" src="{{ Auth::user()->image_url }}" alt="{{ Auth::user()->name }}">
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <svg class="h-20 w-20 text-gray-400 dark:text-gray-500 rounded-full object-cover" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Name and Email (Display Only) --}}
                        <div class="ms-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user['owner_email'] }}</p>

                            {{-- This styled label triggers the hidden file input --}}
                            <div class="mt-3">
                                <label for="photo-input" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-500 cursor-pointer">
                                    <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" /><path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" /></svg>
                                    <span>Upload New Photo</span>
                                </label>
                                <input type="file" id="photo-input" name="profile_picture" class="hidden">
                                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            {{-- Update Profile Information Form --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-2xl">
                    <div class="w-full">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password Form --}}
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-2xl">
                    <div class="w-full">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const photoInput = document.getElementById('photo-input');
                const photoForm = document.getElementById('photo-upload-form');

                if (photoInput && photoForm) {
                    // When a new file is selected, automatically submit the form.
                    photoInput.addEventListener('change', function () {
                        if (this.files.length > 0) {
                            photoForm.submit();
                        }
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>

