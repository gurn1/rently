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
        <div class="panel gap-5">
            <div class="text-gray-500 font-semibold">
                <a class="flex items-center" href="{{ route('manager.properties.index') }}">
                    <span class="material-symbols-outlined font-light text-properties">other_houses</span>
                    <p class="pl-2">Properties</p>
                </a>
            </div>
            <div class="flex flex-col mt-3">
                <a href="{{ route('manager.properties.index') }}">
                    <p class="text-5xl font-bold text-gray-700 leading-none">
                        {{ auth()->user()->properties->count() }}
                    </p>
                </a>
            </div>
        </div>
        <div class="panel gap-5">
            <div class="text-gray-500 font-semibold">
                <a class="flex items-center" href="{{ route('manager.users.index') }}">
                    <span class="material-symbols-outlined font-light text-users">groups</span>
                    <p class="pl-2">Tenants</p>
                </a>
            </div>
            <div class="flex flex-col mt-3">
                <a href="{{ route('manager.users.index') }}">
                    <p class="text-5xl font-bold text-gray-700 leading-none">
                        {{ auth()->user()->tenants->count() }}
                    </p>
                </a>
            </div>
        </div>
        <div class="panel gap-5">
            <div class="text-gray-500 font-semibold">
                <a class="flex items-center" href="{{ route('manager.leases.index') }}">
                    <span class="material-symbols-outlined font-light text-leases">contract</span>
                    <p class="pl-2">Leases</p>
                </a>
            </div>
            <div class="flex flex-col mt-3">
                <a href="{{ route('manager.leases.index') }}">
                    <p class="text-5xl font-bold text-gray-700 leading-none">
                        {{ auth()->user()->properties->sum(fn($p) => $p->leases->where('status', 'active')->count()) }}
                    </p>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
        {{-- Outstanding work orders --}}
        <div class="panel">
            <h2 class="font-semibold text-gray-700 mb-4">Outstanding Work Orders</h2>

            @if($workOrders->isEmpty())
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                    <p class="text-xl">No work orders yet.</p>
                </div>
            @else
                <div class="overflow-hidden">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date Created</th>
                                <th>Issue</th>
                                <th class="text-right">Priority</th>
                                <th class="text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workOrders as $workOrder)
                                <tr>
                                    <td>{{ $workOrder->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('manager.work-orders.show', $workOrder) }}" class="font-medium text-gray-900">{{ $workOrder->title }}</a>
                                        <div class="text-gray-400">{{ $workOrder->property->title }}</div>
                                        <div>By: <a href="{{ route('manager.users.index') }}" class="text-indigo-600">{{ $workOrder->raisedBy->first_name }} {{ $workOrder->raisedBy->last_name }}</a></div>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                            ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                            ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                            'bg-gray-100 text-gray-600')) }}">
                                            {{ $workOrder->priority }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                            ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                                            'bg-yellow-100 text-yellow-700') }}">
                                            {{ str_replace('_', ' ', $workOrder->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Recent properties --}}
        <div class="panel">
            <h2 class="font-semibold text-gray-700 mb-4">Recent Properties</h2>
            <div>
            @if ($recentProperties->isEmpty())
                <p class="text-gray-400 text-sm">No properties yet.</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th class="text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProperties as $property)
                            <tr>
                                <td>
                                    <a href="{{ route('manager.properties.show', $property) }}"><span>{{ $property->title }}</span></a>
                                </td>
                                <td class="text-right">
                                    <span class="text-xs px-2 py-1 rounded capitalize
                                        {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ str_replace('_', ' ', $property->availability_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
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
@endsection