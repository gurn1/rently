@extends('layouts.portal')

@section('title', 'Work Orders')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">All Work Orders</h1>
        <p class="text-gray-500 mt-1">{{ $workOrders->total() }} work orders</p>
    </div>

    @if($workOrders->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No work orders found.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Property</th>
                        <th>Raised By</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrders as $workOrder)
                        <tr>
                            <td class="text-gray-900">{{ $workOrder->title }}</td>
                            <td>{{ $workOrder->property->title }}</td>
                            <td>
                                {{ $workOrder->raisedBy->first_name }} {{ $workOrder->raisedBy->last_name }}
                            </td>
                            <td>
                                {{ $workOrder->assignedTo?->first_name ?? '—' }}
                                {{ $workOrder->assignedTo?->last_name ?? '' }}
                            </td>
                            <td>
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                       ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                       ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-gray-100')) }}">
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
                                <a href="{{ route('admin.work-orders.show', $workOrder) }}"
                                   class="text-indigo-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $workOrders->links() }}
        </div>
    @endif
@endsection