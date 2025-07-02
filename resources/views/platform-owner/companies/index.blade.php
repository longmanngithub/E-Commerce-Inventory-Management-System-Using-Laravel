<x-app-layout>
    <x-slot name="header">
        <div class="hidden sm:flex flex-col">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                {{ __('Companies') }}
            </h2>
            <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company information</h4>
        </div>
    </x-slot>

    <div class="py-12 px-4 lg:px-12 h-full">
        <div class="max-w-full mx-auto h-full">

            <div class="lg:hidden flex flex-col mb-6 px-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    {{ __('Companies') }}
                </h2>
                <h4 class="mt-1 text-sm leading-tight dark:text-gray-500">View company information</h4>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($companies as $company)
                    <a href="{{ route('company.show', $company['id']) }}">
                        <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 dark:bg-gray-800">
                            <div class="flex items-start justify-start space-x-4 mb-4">
                                @if($company['imageUrl'])
                                    <div class="w-fit h-16 rounded-2xl">
                                        <img src="{{ $company['imageUrl'] }}" alt="{{ $company['name'] }}" class="h-full w-auto rounded-md object-contain">
                                    </div>
                                @else
                                    <div class="h-16 w-16 rounded-md bg-gray-200 flex items-center justify-center">
                                        <span class="text-xl font-bold text-gray-500">{{ substr($company['name'], 0, 1) }}</span>
                                    </div>
                                @endif

                            </div>

                            <div class="text-sm space-y-2 dark:text-white dark:font-extralight">
                                <h3 class="font-bold text-xl text-gray-900 dark:text-white">{{ $company['name'] }}</h3>
                                <p>Status:
                                    <span class="font-semibold  {{ $company['status'] === 'Active' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $company['status'] }}
                                    </span>
                                </p>
                                @if($company['subscription'])
                                    <p>Plan: <span class="font-semibold">{{ $company['subscription']['plan'] }}</span></p>
                                    <p>Payment: <span class="font-semibold {{ $company['subscription']['status'] === 'Paid' ? 'text-green-600' : 'text-gray-500' }}">{{ $company['subscription']['status'] }}</span></p>
                                    <p>Next Billing: <span class="font-semibold">{{ $company['subscription']['nextBillingDate'] ? \Carbon\Carbon::parse($company['subscription']['nextBillingDate'])->format('M d, Y') : 'N/A' }}</span></p>
                                @endif
                            </div>

                        </div>
                    </a>
                @empty
                    <p>No companies found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
