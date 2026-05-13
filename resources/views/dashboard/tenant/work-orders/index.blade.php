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
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No work orders yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($workOrders as $workOrder)
                <a href="{{ route('tenant.work-orders.show', $workOrder) }}"
                   class="block bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ $workOrder->title }}</h2>
                            <p class="text-gray-500 text-sm mt-1">{{ $workOrder->property->title }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $workOrder->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                   ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                   ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                   'bg-gray-100 text-gray-600')) }}">
                                {{ $workOrder->priority }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                   ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                                   'bg-yellow-100 text-yellow-700') }}">
                                {{ str_replace('_', ' ', $workOrder->status) }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $workOrders->links() }}
        </div>
    @endif
@endsection