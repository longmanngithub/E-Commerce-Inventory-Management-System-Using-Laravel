<x-full-width-layout>
    <div class="bg-white">
        <main class="max-w-2xl mx-auto pt-16 pb-24 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Complete Your Purchase</h1>

            <form class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16 mt-12" action="{{ route('subscription.store') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan['id'] }}">

                {{-- Left Side: Payment Form --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Payment Information</h3>

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
                                value = value.slice(0, 2) + ' / ' + value.slice(2);
                            }
                            event.target.value = value;
                        }
                    }" class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">

                        {{-- Card Number --}}
                        <div class="sm:col-span-2">
                            <label for="card_number" class="block text-sm font-medium text-gray-700">Card number</label>
                            <div class="mt-1 relative">
                                <input type="text" name="card_number" id="card_number"
                                       class="block w-full border-gray-300 rounded-md shadow-sm pe-12"
                                       placeholder="0000 0000 0000 0000"
                                       x-on:input="formatCardNumber"> {{-- formatting function --}}

                                {{-- Logo --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pe-3">
                                    <img x-show="cardType === 'visa'" src="{{ asset('assets/images/visa.svg') }}" alt="Visa" class="h-8 w-auto">
                                    <img x-show="cardType === 'mastercard'" src="{{ asset('assets/images/mastercard.svg') }}" alt="Mastercard" class="h-8 w-auto">
                                    <img x-show="cardType === 'amex'" src="{{ asset('assets/images/amex.svg') }}" alt="American Express" class="h-8 w-auto">
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('card_number')" class="mt-2" />
                        </div>

                        {{-- Expiration Date --}}
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiration date</label>
                            <div class="mt-1">
                                {{-- formatExpiry function and limits length --}}
                                <input type="text" name="expiry_date" id="expiry_date" class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="MM / YY" x-on:input="formatExpiry" maxlength="7">
                                <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                            </div>
                        </div>

                        {{-- CVV --}}
                        <div>
                            <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                            <div class="mt-1">
                                <input type="text" name="cvv" id="cvv" class="block w-full border-gray-300 rounded-md shadow-sm" placeholder="123" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <x-input-error :messages="$errors->get('cvv')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Billing Address Field --}}
                        <div class="sm:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                            <div class="mt-1">
                                <input type="text" name="billing_address" id="billing_address" class="block w-full border-gray-300 rounded-md shadow-sm">
                                <x-input-error :messages="$errors->get('billing_address')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                        {{-- Right Side: Order Summary & Plan Details --}}
                        <div class="mt-10 lg:mt-0">
                            <h2 class="text-lg font-medium text-gray-900">Order summary</h2>
                            <div class="mt-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $plan['tier'] }} Plan</h3>
                                    <p class="mt-4 flex items-baseline text-gray-900">
                                        <span class="text-4xl font-extrabold tracking-tight">${{ number_format($plan['price'], 2) }}</span>
                                        <span class="ml-1 text-lg font-semibold">/month</span>
                                    </p>

                                    {{-- Plan Features --}}
                                    <ul role="list" class="mt-6 space-y-4 text-sm">
                                        <li class="flex space-x-3"><span>Up to 2500 active products</span></li>
                                        <li class="flex space-x-3"><span>Up to 10 user accounts</span></li>
                                        <li class="flex space-x-3"><span>Advanced features</span></li>
                                    </ul>
                                </div>
                                <div class="border-t border-gray-200 p-6 flex items-center justify-between">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">${{ number_format($plan['price'], 2) }}</dd>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700">
                                    Complete Purchase
                                </button>
                            </div>
                        </div>
            </form>
        </main>
    </div>
</x-full-width-layout>
