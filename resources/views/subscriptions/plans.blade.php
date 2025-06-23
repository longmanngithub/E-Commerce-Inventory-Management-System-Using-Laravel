<x-full-width-layout>
    <div class="pt-12 pb-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                    Choose Your Plan
                </h2>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
                    Get the features you need with flexible pricing options
                </p>
            </div>

            <!-- Main Pricing Cards -->
            <div class="mt-16 grid grid-cols-1 gap-8 lg:grid-cols-3 lg:gap-6">
                @foreach ($plans as $plan)
                    @php
                        $isPopular = ($plan['tier'] === 'Pro');
                        $isBasic = ($plan['tier'] === 'Basic');
                        $isUltimate = ($plan['tier'] === 'Ultimate');
                    @endphp

                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl {{ $isPopular ? 'border-2 border-blue-500 dark:border-blue-400 shadow-xl' : 'border border-gray-200 dark:border-gray-700 shadow-sm' }} overflow-hidden">
                        @if($isPopular)
                            <div class="absolute top-6 left-1/2 transform -translate-x-1/2">
                                <span class="bg-blue-600 dark:bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-medium">Most Popular</span>
                            </div>
                        @endif

                        <div class="p-8 {{ $isPopular ? 'pt-16' : '' }}">
                            <!-- Plan Name -->
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white text-center">{{ $plan['tier'] }}</h3>
                            <p class="text-center text-gray-500 dark:text-gray-400 mt-2">
                                @if($isBasic) Perfect for small businesses
                                @elseif($isPopular) Best for enterprises
                                @else For large organizations @endif
                            </p>

                            <!-- Price -->
                            <div class="mt-6 text-center">
                                <span class="text-5xl font-bold text-gray-900 dark:text-white">${{ number_format($plan['price'], 0) }}</span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">/month</span>
                            </div>

                            <!-- Features List -->
                            <ul class="mt-8 space-y-4">
                                @if($isBasic)
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
                                @elseif($isPopular)
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

                            <!-- CTA Button -->
                            <div class="mt-8">
                                @if ($isChangingPlan)
                                    {{-- For EXISTING users, show a form to instantly change their plan --}}
                                    <form action="{{ route('subscription.change') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $plan['id'] }}">
                                        <button type="submit" class="w-full py-3 px-6 rounded-lg font-semibold transition-colors {{ $isPopular ? 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white' : ($isUltimate ? 'bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white') }}">
                                            Change to {{ $plan['tier'] }}
                                        </button>
                                    </form>
                                @else
                                    {{-- For NEW users, show a link to the checkout page --}}
                                    <a href="{{ route('subscription.checkout', $plan['id']) }}" class="block w-full py-3 px-6 rounded-lg font-semibold text-center transition-colors {{ $isPopular ? 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white' : ($isUltimate ? 'bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white' : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white') }}">
                                        Choose {{ $plan['tier'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Comparison Table Section -->
            <div class="mt-24">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Compare Plans</h3>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">See what's included in each plan to find the perfect fit</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
                    <!-- Plan Headers -->
                    <div class="grid grid-cols-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <div class="p-6">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Features</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose the plan that works for you</p>
                        </div>
                        <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg">Basic</h4>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">${{ $plans[0]['price'] ?? '49' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">/mo</p>
                            <button class="mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-900 dark:text-white rounded-lg font-medium transition-colors">
                                Get Started
                            </button>
                        </div>
                        <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20 relative">
                            <div class="absolute top-2 left-1/2 transform -translate-x-1/2">
                                <span class="bg-blue-600 dark:bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">Most Popular</span>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg mt-6">Pro</h4>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">${{ $plans[1]['price'] ?? '99' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">/mo</p>
                            <button class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                Choose Pro
                            </button>
                        </div>
                        <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                            <h4 class="font-bold text-gray-900 dark:text-white text-lg">Ultimate</h4>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">${{ $plans[2]['price'] ?? '129' }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">/mo</p>
                            <button class="mt-4 px-4 py-2 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-lg font-medium transition-colors">
                                Choose Ultimate
                            </button>
                        </div>
                    </div>

                    <!-- Feature Rows -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-600">
                        <!-- Inventory Management Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <h5 class="font-semibold text-gray-900 dark:text-white">Inventory Management</h5>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Number of active products</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">Up to 500</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20">Up to 2500</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">Unlimited</div>
                        </div>

                        <!-- User Management Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <h5 class="font-semibold text-gray-900 dark:text-white">User Management</h5>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">User accounts</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">1 user</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20">Up to 10</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">Unlimited</div>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Role-based access</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-red-500 dark:text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Analytics & Insights Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <h5 class="font-semibold text-gray-900 dark:text-white">Analytics & Insights</h5>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Basic features</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Dashboard</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Analytics</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-red-500 dark:text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Audit logs</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-red-500 dark:text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Support & Security Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3">
                            <h5 class="font-semibold text-gray-900 dark:text-white">Support & Security</h5>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Support level</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">Email</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20">Priority</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400">24/7 Support</div>
                        </div>
                        <div class="grid grid-cols-4">
                            <div class="p-6 font-medium text-gray-900 dark:text-white">Advanced security</div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-red-500 dark:text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600 bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-5 h-5 text-red-500 dark:text-red-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="p-6 text-center border-l border-gray-200 dark:border-gray-600">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">All plans are not refundable.</p>
                        <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium mt-2 inline-block">Contact by Email</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-full-width-layout>
