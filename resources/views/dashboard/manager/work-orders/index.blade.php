@extends('layouts.portal')

@section('title', 'Work Orders')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Work Orders</h1>
            <p class="text-gray-500 mt-1">{{ $workOrders->total() }} work orders</p>
        </div>
        <a href="{{ route('manager.work-orders.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + New Work Order
        </a>
    </div>

    @if($workOrders->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No work orders yet.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Property</th>
                        <th>Raised By</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrders as $workOrder)
                        <tr>
                            <td class="text-gray-900">
                                {{ $workOrder->title }}
                            </td>
                            <td>
                                {{ $workOrder->property->title }}
                            </td>
                            <td>
                                {{ $workOrder->raisedBy->first_name }} {{ $workOrder->raisedBy->last_name }}
                            </td>
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
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('manager.work-orders.show', $workOrder) }}"
                                       class="text-indigo-600 hover:underline">View</a>
                                    <a href="{{ route('manager.work-orders.edit', $workOrder) }}"
                                       class="text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST"
                                          action="{{ route('manager.work-orders.destroy', $workOrder) }}"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </div>
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