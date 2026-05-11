@extends('layouts.portal')

@section('title', 'Manager Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->first_name }}
        </h1>
        <p class="text-gray-500 mt-1">Here's an overview of your properties and tenants.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">My Properties</p>
            <p class="text-3xl font-bold text-indigo-600">
                {{ auth()->user()->properties->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Active Tenants</p>
            <p class="text-3xl font-bold text-indigo-600">
                {{ auth()->user()->tenants->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Active Leases</p>
            <p class="text-3xl font-bold text-indigo-600">
                {{ auth()->user()->properties->sum(fn($p) => $p->leases->where('status', 'active')->count()) }}
            </p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-gray-700 mb-4">Quick Actions</h2>
            <div class="flex flex-col gap-3">
                <a href="{{ route('manager.properties.create') }}"
                   class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
                    + Add New Property
                </a>
                <a href="{{ route('manager.properties.index') }}"
                   class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition text-sm">
                    View All Properties
                </a>
            </div>
        </div>

        {{-- Recent properties --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-gray-700 mb-4">Recent Properties</h2>
            @forelse(auth()->user()->properties->take(3) as $property)
                <a href="{{ route('manager.properties.show', $property) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <span>{{ $property->title }}</span>
                    <span class="text-xs px-2 py-1 rounded capitalize
                        {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ str_replace('_', ' ', $property->availability_status) }}
                    </span>
                </a>
            @empty
                <p class="text-gray-400 text-sm">No properties yet.</p>
            @endforelse
        </div>
    </div>
@endsection