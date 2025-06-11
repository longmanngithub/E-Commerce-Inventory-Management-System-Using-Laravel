<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
            @if(Auth::guard('company_admin')->check())
                <a href="{{ route('management.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Invite User
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>

                                {{-- Display name --}}
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->admin_name ?? $user->staff_name }}</td>

                                {{-- Display email --}}
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->admin_email ?? $user->staff_email }}</td>

                                {{-- Display role --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user instanceof \App\Models\CompanyAdmin)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Admin
                                            </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Staff
                                            </span>
                                    @endif
                                </td>

                                {{-- Display actions--}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                    <div class="flex items-center justify-end space-x-4">
                                        @if($user instanceof \App\Models\CompanyStaff)
                                            {{-- Actions for Staff --}}
                                            <a href="{{ route('management.users.edit.staff', $user->staff_id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('management.users.destroy.staff', $user->staff_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        @else
                                            {{-- Actions for Admins --}}
                                            @if(Auth::user()->admin_id !== $user->admin_id)
                                                <form action="{{ route('management.users.destroy.admin', $user->admin_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
