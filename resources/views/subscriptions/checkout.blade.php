<x-full-width-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <main class="max-w-6xl mx-auto pt-8 pb-24 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex mt-12">
                <button class="flex me-3 items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-4 transition-colors">
                    <a href="{{ url()->previous() }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                </button>
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Complete Your Purchase</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Inventory Management System - Pro Plan</p>
                </div>
            </div>

            <form action="{{ route('subscription.store') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan['id'] }}">

                <!-- Plan Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Pro Plan</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Advanced inventory management with analytics</p>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($plan['price'], 0) }}</span>
                                <span class="text-gray-600 dark:text-gray-400 ml-1">per month</span>
                            </div>
                        </div>

                        {{-- Plan Features --}}
                        <ul role="list" class="space-y-3 text-sm">
                            @if($plan['id'] === 1)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Up to 500 active products</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Single user account</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Basic features</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Email support</span>
                                </li>
                            @elseif($plan['id'] === 2)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Up to 2500 active products</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Up to 10 user accounts</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Advanced features</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Email notification</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Priority support</span>
                                </li>
                            @else
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Everything in Pro</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Unlimited products</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Unlimited user accounts</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">Advanced security</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">24/7 support</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Payment Information</h3>

                    {{-- We initialize an Alpine.js component with our functions --}}
                    <div x-data="{
                            cardType: '',
                            formatCardNumber(event) {
                                let value = event.target.value.replace(/\D/g, '').slice(0, 16);
                                value = value.replace(/(\d{4})/g, '$1 ').trim();
                                event.target.value = value;
                                this.detectCardType(value);
                            },
                            detectCardType(number) {
                                number = number.replace(/\s/g, '');
                                if (/^4/.test(number)) { this.cardType = 'visa'; }
                                else if (/^5[1-5]/.test(number)) { this.cardType = 'mastercard'; }
                                else if (/^3[47]/.test(number)) { this.cardType = 'amex'; }
                                else { this.cardType = ''; }
                            },
                            formatExpiry(event) {
                                let value = event.target.value.replace(/\D/g, '').slice(0, 4);
                                if (value.length > 2) {
                                    value = value.slice(0, 2) + '/' + value.slice(2);
                                }
                                event.target.value = value;
                            }
                        }" class="space-y-6">

                        {{-- Card Number --}}
                        <div>
                            <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card Information</label>
                            <div class="relative">
                                <input type="text" name="card_number" id="card_number"
                                       class="block w-full px-3 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                       placeholder="•••• •••• •••• 1234"
                                       x-on:input="formatCardNumber">

                                {{-- Card Logo --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <div x-show="cardType === 'visa'" class="w-8 h-5 bg-blue-600 rounded flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">VISA</span>
                                    </div>
                                    <div x-show="cardType === 'mastercard'" class="flex">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full -ml-1"></div>
                                    </div>
                                    <div x-show="cardType === 'amex'" class="w-8 h-5 bg-blue-500 rounded flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">AMEX</span>
                                    </div>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('card_number')" class="mt-2" />
                        </div>

                        {{-- Expiration Date and CVV --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date"
                                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                       placeholder="12/25"
                                       x-on:input="formatExpiry"
                                       maxlength="7">
                                <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                            </div>

                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVV</label>
                                <input type="text" name="cvv" id="cvv"
                                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                       placeholder="123"
                                       maxlength="3"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <x-input-error :messages="$errors->get('cvv')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Billing Address Field --}}
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Billing Address</label>
                            <input type="text" name="billing_address" id="billing_address"
                                   class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                   placeholder="123 Main Street, San Francisco, CA 94102">
                            <x-input-error :messages="$errors->get('billing_address')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Summary</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                                <dt>Subtotal</dt>
                                <dd>${{ number_format($plan['price'], 2) }}</dd>
                            </div>
                            <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                                <dt>Tax</dt>
                                <dd>$0.00</dd>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                                <div class="flex justify-between text-lg font-semibold text-gray-900 dark:text-white">
                                    <dt>Total</dt>
                                    <dd>${{ number_format($plan['price'], 2) }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complete Purchase Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-2xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Complete Purchase
                </button>

                <!-- Security Notice -->
                <div class="flex items-center justify-center mt-4 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Your payment information is encrypted and secure
                </div>

            </form>
        </main>
    </div>
</x-full-width-layout>
