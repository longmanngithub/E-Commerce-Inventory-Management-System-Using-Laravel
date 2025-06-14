<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($companies as $company)
                    <a href="{{ route('company.show', $company['id']) }}">
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                            <div class="flex items-center space-x-4 mb-4">
                                @if($company['imageUrl'])
                                    <img src="{{ $company['imageUrl'] }}" alt="{{ $company['name'] }}" class="h-12 w-12 rounded-md object-contain">
                                @else
                                    <div class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                                        <span class="text-xl font-bold text-gray-500">{{ substr($company['name'], 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="font-bold text-lg text-gray-900">{{ $company['name'] }}</h3>
                            </div>

                            <div class="text-sm space-y-2">
                                <p>Status:
                                    <span class="font-semibold {{ $company['status'] === 'Active' ? 'text-green-600' : 'text-red-600' }}">
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
