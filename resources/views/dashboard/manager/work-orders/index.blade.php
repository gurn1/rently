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
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Title</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Property</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Raised By</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Priority</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($workOrders as $workOrder)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $workOrder->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $workOrder->property->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $workOrder->raisedBy->first_name }} {{ $workOrder->raisedBy->last_name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                       ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                       ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-gray-100 text-gray-600')) }}">
                                    {{ $workOrder->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                       ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                                       'bg-yellow-100 text-yellow-700') }}">
                                    {{ str_replace('_', ' ', $workOrder->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
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