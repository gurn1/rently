@extends('layouts.portal')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">System-wide overview for {{ now()->format('F Y') }}.</p>
    </div>

    {{-- Top stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Total Users</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $userStats['total'] }}</p>
            <div class="mt-3 flex gap-3 text-xs text-gray-400">
                <span>{{ $userStats['tenants'] }} tenants</span>
                <span>{{ $userStats['managers'] }} managers</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Properties</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $propertyStats['total'] }}</p>
            <div class="mt-3 flex gap-3 text-xs">
                <span class="text-green-600">{{ $propertyStats['available'] }} available</span>
                <span class="text-red-500">{{ $propertyStats['occupied'] }} occupied</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Active Leases</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $leaseStats['active'] }}</p>
            <div class="mt-3 flex gap-3 text-xs text-gray-400">
                <span>{{ $leaseStats['pending'] }} pending</span>
                <span>{{ $leaseStats['terminated'] }} terminated</span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Revenue This Month</p>
            <p class="text-3xl font-bold text-green-600">£{{ number_format($paymentStats['this_month'], 0) }}</p>
            <div class="mt-3 flex gap-3 text-xs text-gray-400">
                <span>£{{ number_format($paymentStats['total_collected'], 0) }} total</span>
            </div>
        </div>
    </div>

    {{-- Secondary stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Pending Payments</p>
            <p class="text-2xl font-bold text-yellow-500">£{{ number_format($paymentStats['total_pending'], 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Failed Payments</p>
            <p class="text-2xl font-bold text-red-500">{{ $paymentStats['total_failed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Open Work Orders</p>
            <p class="text-2xl font-bold text-orange-500">{{ $workOrderStats['open'] }}</p>
            @if($workOrderStats['urgent'] > 0)
                <p class="text-xs text-red-500 mt-1">{{ $workOrderStats['urgent'] }} urgent</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent leases --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Recent Leases</h2>
                <a href="{{ route('admin.leases.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse($recentLeases as $lease)
                <a href="{{ route('admin.leases.show', $lease) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <div>
                        <p class="font-medium text-gray-900">{{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}</p>
                        <p class="text-gray-400 text-xs">{{ $lease->property->title }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded capitalize
                        {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                           ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           'bg-red-100 text-red-700') }}">
                        {{ $lease->status }}
                    </span>
                </a>
            @empty
                <p class="text-gray-400 text-sm">No leases yet.</p>
            @endforelse
        </div>

        {{-- Open work orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Open Work Orders</h2>
                <a href="{{ route('admin.work-orders.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse($recentWorkOrders as $workOrder)
                <a href="{{ route('admin.work-orders.show', $workOrder) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <div>
                        <p class="font-medium text-gray-900">{{ $workOrder->title }}</p>
                        <p class="text-gray-400 text-xs">{{ $workOrder->property->title }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded capitalize
                        {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                           ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                           'bg-yellow-100 text-yellow-700') }}">
                        {{ $workOrder->priority }}
                    </span>
                </a>
            @empty
                <p class="text-gray-400 text-sm">No open work orders.</p>
            @endforelse
        </div>

        {{-- Failed payments --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Failed Payments</h2>
                <a href="{{ route('admin.payments.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse($failedPayments as $payment)
                <a href="{{ route('admin.payments.show', $payment) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <div>
                        <p class="font-medium text-gray-900">{{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}</p>
                        <p class="text-gray-400 text-xs">{{ $payment->lease->property->title }}</p>
                    </div>
                    <span class="font-medium text-red-500">£{{ number_format($payment->amount, 0) }}</span>
                </a>
            @empty
                <div class="text-center py-4">
                    <p class="text-green-600 text-sm">✓ No failed payments</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick links --}}
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.create') }}"
               class="text-center border border-gray-200 rounded p-4 hover:border-indigo-400 hover:text-indigo-600 transition text-sm">
                + New User
            </a>
            <a href="{{ route('admin.properties.create') }}"
               class="text-center border border-gray-200 rounded p-4 hover:border-indigo-400 hover:text-indigo-600 transition text-sm">
                + New Property
            </a>
            <a href="{{ route('admin.leases.create') }}"
               class="text-center border border-gray-200 rounded p-4 hover:border-indigo-400 hover:text-indigo-600 transition text-sm">
                + New Lease
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="text-center border border-gray-200 rounded p-4 hover:border-indigo-400 hover:text-indigo-600 transition text-sm">
                ⚙ Settings
            </a>
        </div>
    </div>
@endsection