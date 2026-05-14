@extends('layouts.portal')

@section('title', $user->first_name . ' ' . $user->last_name)

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to users
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                {{ $user->first_name }} {{ $user->last_name }}
            </h1>
            <span class="text-xs px-2 py-1 rounded-full capitalize
                {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-700' :
                   ($user->hasRole('property_manager') ? 'bg-blue-100 text-blue-700' :
                   'bg-green-100 text-green-700') }}">
                {{ str_replace('_', ' ', $user->roles->first()?->name ?? 'No role') }}
            </span>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}"
           class="border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition text-sm">
            Edit User
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            {{-- Account details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Account Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Joined</p>
                        <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    @if($user->profile)
                        <div>
                            <p class="text-gray-400">Legal Name</p>
                            <p class="font-medium">{{ $user->profile->legal_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Phone</p>
                            <p class="font-medium">{{ $user->profile->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Address</p>
                            <p class="font-medium">{{ $user->profile->address ?? '—' }}</p>
                        </div>
                        @if($user->profile->emergency_contact_name)
                            <div>
                                <p class="text-gray-400">Emergency Contact</p>
                                <p class="font-medium">
                                    {{ $user->profile->emergency_contact_name }}
                                    ({{ $user->profile->emergency_contact_relationship }})
                                    — {{ $user->profile->emergency_contact_phone }}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Properties (if manager) --}}
            @role('property_manager', $user)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Properties</h2>
                    @forelse($user->properties as $property)
                        <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                            <p class="font-medium text-gray-900">{{ $property->title }}</p>
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ str_replace('_', ' ', $property->availability_status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">No properties assigned.</p>
                    @endforelse
                </div>
            @endrole

            {{-- Leases (if tenant) --}}
            @if($user->hasRole('tenant'))
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Leases</h2>
                    @forelse($user->leases as $lease)
                        <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                            <div>
                                <p class="font-medium text-gray-900">{{ $lease->property->title }}</p>
                                <p class="text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}
                                    — {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'Ongoing' }}
                                </p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                                   ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                   'bg-red-100 text-red-700') }}">
                                {{ $lease->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">No leases found.</p>
                    @endforelse
                </div>
            @endif

            {{-- Assigned tenants (if manager) --}}
            @if($user->hasRole('property_manager'))
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Assigned Tenants</h2>

                    @forelse($user->tenants as $tenant)
                        <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                            <div>
                                <p class="font-medium text-gray-900">{{ $tenant->first_name }} {{ $tenant->last_name }}</p>
                                <p class="text-gray-400 text-xs">{{ $tenant->email }}</p>
                            </div>
                            <form method="POST"
                                  action="{{ route('admin.users.remove-tenant', [$user, $tenant]) }}"
                                  onsubmit="return confirm('Remove this tenant?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">No tenants assigned.</p>
                    @endforelse

                    {{-- Assign tenant form --}}
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm font-medium text-gray-700 mb-3">Assign a Tenant</p>
                        <form method="POST" action="{{ route('admin.users.assign-tenant', $user) }}"
                              class="flex gap-3">
                            @csrf
                            <select name="tenant_id"
                                    class="flex-1 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">Select a tenant</option>
                                @foreach(App\Models\User::role('tenant')->get() as $tenant)
                                    <option value="{{ $tenant->id }}">
                                        {{ $tenant->first_name }} {{ $tenant->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
                                Assign
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Quick Actions</h2>
                <div class="space-y-3 text-sm">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="block w-full text-center border border-indigo-600 text-indigo-600 py-2 rounded hover:bg-indigo-50 transition">
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection