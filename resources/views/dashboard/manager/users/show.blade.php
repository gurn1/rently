@extends('layouts.portal')

@section('title', $user->first_name . ' ' . $user->last_name)

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('manager.users.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to users
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                {{ $user->first_name }} {{ $user->last_name }}
            </h1>
            <span class="text-xs px-2 py-1 rounded-full capitalize bg-green-100 text-green-700">
                {{ str_replace('_', ' ', $user->roles->first()?->name ?? 'No role') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            {{-- Account details --}}
            <div class="panel">
                <h2 class="panel-title">Account Details</h2>
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
            @if($user->hasRole('property_manager'))
                <div class="panel">
                    <h2 class="panel-title">Properties</h2>
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
            @endif

            {{-- Leases (if tenant) --}}
            @if($user->hasRole('tenant'))
                <div class="panel">
                    <h2 class="panel-title">Leases</h2>
                    @forelse($user->leases as $lease)
                        <div class="py-3 px-4 flex justify-between items-center py-2 rounded-lg border-gray-100 border text-sm mb-2 last:mb-0">
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
        </div>
    </div>
@endsection