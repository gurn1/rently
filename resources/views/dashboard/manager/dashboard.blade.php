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
        <div class="panel flex gap-5">
            <div class="aspect-square w-[20%] bg-properties rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined font-light !text-5xl mt-[-5px]  text-white">other_houses</span>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Properties</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">
                    {{ auth()->user()->properties->count() }}
                </p>
            </div>
        </div>
        <div class="panel flex gap-5">
            <div class="aspect-square w-[20%] bg-users rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined font-light !text-5xl mt-[-5px]  text-white">groups</span>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Tenants</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">
                    {{ auth()->user()->tenants->count() }}
                </p>
            </div>
        </div>
        <div class="panel flex gap-5">
            <div class="aspect-square w-[20%] bg-leases rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined font-light !text-5xl mt-[-5px]  text-white">contract</span>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Leases</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">
                    {{ auth()->user()->properties->sum(fn($p) => $p->leases->where('status', 'active')->count()) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="panel">
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
        <div class="panel">
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