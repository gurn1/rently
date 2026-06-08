@extends('layouts.portal')

@section('title', 'Work Orders')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Work Orders</h1>
            <p class="text-gray-500 mt-1">Your maintenance and repair requests.</p>
        </div>
        <a href="{{ route('tenant.work-orders.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + New Request
        </a>
    </div>

    @if($workOrders->isEmpty())
        <div class="panel">
            <p class="text-xl">No work orders yet.</p>
        </div>
    @else
        <div class="space-y-4">
            <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Property</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($workOrders as $workOrder)
                <tr>
                    <td class="font-semibold text-gray-900">
                        <a href="{{ route('tenant.work-orders.show', $workOrder) }}">
                            {{ $workOrder->title }}
                        </a>
                    </td>
                    <td>{{ $workOrder->property->title }}</td>
                    <td>{{ $workOrder->created_at->diffForHumans() }}</td>
                    <td>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                'bg-gray-100 text-gray-600')) }}">
                            {{ $workOrder->priority }}
                        </span>
                    </td>
                    <td>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                                'bg-yellow-100 text-yellow-700') }}">
                            {{ str_replace('_', ' ', $workOrder->status) }}
                        </span>
                    </td>
                    <td>
                        <x-outline-button href="{{ route('tenant.work-orders.show', $workOrder) }}"
                            class="text-indigo-600 border-indigo-600"
                        >View</x-outline-button>
                    </td>
                </tr>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $workOrders->links() }}
        </div>
    @endif
@endsection