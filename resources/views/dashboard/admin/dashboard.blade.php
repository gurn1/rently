@extends('layouts.portal')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">System-wide overview for {{ now()->format('F Y') }}.</p>
    </div>

    {{-- Top stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="panel flex gap-5">
            <div class="w-[30%]">
                <div class="aspect-square w-full bg-users rounded-full flex items-center justify-center">
                    <a href="{{ route('admin.users.index') }}"><span class="material-symbols-outlined font-light !text-5xl text-white">groups</span></a>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Total Users</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">{{ $userStats['total'] }}</p>
                <div class="mt-1 flex gap-3 text-xs text-gray-400">
                    <span>{{ $userStats['tenants'] }} tenants</span>
                    <span>{{ $userStats['managers'] }} managers</span>
                </div>
            </div>
        </div>
        <div class="panel flex gap-5">
            <div class="w-[30%]">
                <div class="aspect-square w-full bg-properties rounded-full flex items-center justify-center">
                    <a href="{{ route('admin.properties.index') }}"><span class="material-symbols-outlined font-light !text-5xl text-white">other_houses</span></a>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Properties</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">{{ $propertyStats['total'] }}</p>
                <div class="mt-1 flex gap-3 text-xs">
                    <span class="text-green-600">{{ $propertyStats['available'] }} available</span>
                    <span class="text-red-500">{{ $propertyStats['occupied'] }} occupied</span>
                </div>
            </div>
        </div>
        <div class="panel flex gap-5">
            <div class="w-[30%]">
                <div class="aspect-square w-full bg-leases rounded-full flex items-center justify-center">
                    <a href="{{ route('admin.leases.index') }}"><span class="material-symbols-outlined font-light !text-5xl text-white">contract</span></a>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-xl font-semibold leading-none mb-1">Active Leases</p>
                <p class="text-4xl font-bold text-gray-700 leading-none">{{ $leaseStats['active'] }}</p>
                <div class="mt-1 flex gap-3 text-xs text-gray-400">
                    <span>{{ $leaseStats['pending'] }} pending</span>
                    <span>{{ $leaseStats['terminated'] }} terminated</span>
                </div>
            </div>
        </div>
        <div class="panel">
            <p class="text-xl font-semibold leading-none mb-1">Revenue This Month</p>
            <p class="text-4xl font-bold text-green-600 leading-none">£{{ number_format($paymentStats['this_month'], 0) }}</p>
            <div class="mt-1 flex gap-3 text-xs text-gray-400">
                <span>£{{ number_format($paymentStats['total_collected'], 0) }} total</span>
            </div>
        </div>
    </div>

    {{-- Secondary stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Pending Payments</p>
            <p class="text-2xl font-bold text-yellow-500">£{{ number_format($paymentStats['total_pending'], 0) }}</p>
        </div>
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Failed Payments</p>
            <p class="text-2xl font-bold text-red-500">{{ $paymentStats['total_failed'] }}</p>
        </div>
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Open Work Orders</p>
            <p class="text-2xl font-bold text-orange-500">{{ $workOrderStats['open'] }}</p>
            @if($workOrderStats['urgent'] > 0)
                <p class="text-xs text-red-500 mt-1">{{ $workOrderStats['urgent'] }} urgent</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent leases --}}
        <div class="panel">
            <div class="flex justify-between items-center mb-4">
                <h2 class="panel-title mb-0">Recent Leases</h2>
                <a href="{{ route('admin.leases.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>

            @if($recentLeases->isEmpty())
                <div class="text-center py-2 rounded-lg bg-gray-50">
                    <p class="text-gray-400 text-sm">No leases yet.</p>
                </div>
            @else
                <div>
                    <table class="data-table small">
                        <thead>
                            <th>Tenant</th>
                            <th class="text-right">Status</th>
                        </thead>
                        <tbody>
                            @foreach($recentLeases as $lease)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.leases.show', $lease) }}">
                                        <p class="font-medium text-gray-900">{{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}</p>
                                        <p class="text-gray-400 text-xs">{{ $lease->property->title }}</p>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                                            ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                            'bg-red-100 text-red-700') }}">
                                            {{ $lease->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>

        {{-- Open work orders --}}
        <div class="panel">
            <div class="flex justify-between items-center mb-4">
                <h2 class="panel-title mb-0">Open Work Orders</h2>
                <a href="{{ route('admin.work-orders.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>

            <div>
                @if($recentWorkOrders->isEmpty())
                    <div class="text-center py-2 rounded-lg bg-gray-50">
                        <p class="text-gray-400 text-sm">No open work orders.</p>
                    </div>
                @else
                    <table class="data-table small">
                        <thead>
                            <th>Issue</th>
                            <th class="text-right">Status</th>
                        </thead>
                        <tbody>
                            @foreach($recentWorkOrders as $workOrder)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.work-orders.show', $workOrder) }}">
                                            <p class="font-medium text-gray-900">{{ $workOrder->title }}</p>
                                            <p class="text-gray-400 text-xs">{{ $workOrder->property->title }}</p>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                            ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                            'bg-yellow-100 text-yellow-700') }}">
                                            {{ $workOrder->priority }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            
        </div>

        {{-- Failed payments --}}
        <div class="panel">
            <div class="flex justify-between items-center mb-4">
                <h2 class="panel-title mb-0">Failed Payments</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>

            @if ($failedPayments->isEmpty())
                <div class="text-center py-2 rounded-lg bg-green-100">
                    <p class="text-green-600 text-sm">✓ No failed payments</p>
                </div>
            @else
                <table class="data-table small">
                    <thead>
                        <th>Tenant</th>
                        <th class="text-right">Amount</th>
                    </thead>
                    <tbody>
                        @foreach($failedPayments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment) }}">
                                        <p class="font-medium text-gray-900">{{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}</p>
                                        <p class="text-gray-400 text-xs">{{ $payment->lease->property->title }}</p>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <span class="font-medium text-red-500">£{{ number_format($payment->amount, 0) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif
        </div>
    </div>

    {{-- Quick links --}}
    <div class="mt-8 panel">
        <h2 class="panel-title">Quick Actions</h2>
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