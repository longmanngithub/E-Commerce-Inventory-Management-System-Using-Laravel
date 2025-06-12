<x-full-width-layout>
    <div class="pt-12 pb-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Choose Your Plan
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Get the features you need with flexible pricing options.
                </p>
            </div>

            {{-- Pricing Cards --}}
            <div class="mt-12 space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
                @foreach ($plans as $plan)
                    {{-- We determine the styling based on the plan name --}}
                    @php
                        $isPopular = ($plan->subscription_tier === 'Pro');
                    @endphp
                    <div class="relative p-8 bg-white border rounded-2xl shadow-sm flex flex-col {{ $isPopular ? 'border-indigo-600' : 'border-gray-200' }}">
                        @if($isPopular)
                            <div class="absolute top-0 -translate-y-1/2 transform px-3 py-1 text-sm font-semibold tracking-wide text-white bg-indigo-600 rounded-full shadow-md">Most Popular</div>
                        @endif

                        <h3 class="text-2xl font-semibold text-gray-900">{{ $plan->subscription_tier }}</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">${{ number_format($plan->subscription_price, 0) }}</span>
                            <span class="ml-1 text-xl font-semibold">/month</span>
                        </p>

                        {{-- This is a simplified list of features from your design --}}
                        <ul role="list" class="mt-6 space-y-4 flex-1">
                            <li class="flex space-x-3"><span class="text-green-500">&#10003;</span><span>
                                @if($plan->subscription_tier === 'Basic') Up to 500 active products
                                    @elseif($plan->subscription_tier === 'Pro') Up to 2500 active products
                                    @else Unlimited products @endif
                            </span></li>
                            <li class="flex space-x-3"><span class="text-green-500">&#10003;</span><span>
                                @if($plan->subscription_tier === 'Basic') Single user account
                                    @elseif($plan->subscription_tier === 'Pro') Up to 10 user accounts
                                    @else Unlimited user accounts @endif
                            </span></li>
                            <li class="flex space-x-3"><span class="text-green-500">&#10003;</span><span>
                                @if($plan->subscription_tier === 'Pro' || $plan->subscription_tier === 'Ultimate') Advanced features @else Basic features @endif
                            </span></li>
                        </ul>


                            @if ($isChangingPlan)
                                {{-- For EXISTING users, show a form to instantly change their plan --}}
                                <form action="{{ route('subscription.change') }}" method="POST" class="mt-8">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->subscription_id }}">
                                    <button type="submit" class="block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium {{ $isPopular ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                                        Change to {{ $plan->subscription_tier }}
                                    </button>
                                </form>
                            @else
                                {{-- For NEW users, show a link to the checkout page --}}
                                <a href="{{ route('subscription.checkout', $plan->subscription_id) }}" class="mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium {{ $isPopular ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                                    Choose {{ $plan->subscription_tier }}
                                </a>
                            @endif

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-full-width-layout>
