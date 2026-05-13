@extends('layouts.portal')

@section('title', $workOrder->title)

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('admin.work-orders.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to work orders
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $workOrder->title }}</h1>
            <p class="text-gray-500">{{ $workOrder->property->title }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <p class="text-gray-400">Priority</p>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $workOrder->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                               ($workOrder->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                               ($workOrder->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
                               'bg-gray-100 text-gray-600')) }}">
                            {{ $workOrder->priority }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400">Status</p>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                               ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                               'bg-yellow-100 text-yellow-700') }}">
                            {{ str_replace('_', ' ', $workOrder->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400">Raised By</p>
                        <p class="font-medium">{{ $workOrder->raisedBy->first_name }} {{ $workOrder->raisedBy->last_name }}</p>
                    </div>
                    @if($workOrder->assignedTo)
                        <div>
                            <p class="text-gray-400">Assigned To</p>
                            <p class="font-medium">{{ $workOrder->assignedTo->first_name }} {{ $workOrder->assignedTo->last_name }}</p>
                        </div>
                    @endif
                    @if($workOrder->resolved_at)
                        <div>
                            <p class="text-gray-400">Resolved At</p>
                            <p class="font-medium">{{ $workOrder->resolved_at->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
                <div class="pt-4 border-t">
                    <p class="text-gray-400 text-sm mb-2">Description</p>
                    <p class="text-sm text-gray-700">{!! nl2br(e($workOrder->description)) !!}</p>
                </div>
            </div>

            {{-- Updates --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Updates</h2>
                <div class="space-y-4">
                    @forelse($workOrder->updates as $update)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($update->user->first_name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $update->user->first_name }} {{ $update->user->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $update->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $update->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm">No updates yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Property</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">{{ $workOrder->property->title }}</p>
                    <p class="text-gray-500">{{ $workOrder->property->address }}</p>
                    <a href="{{ route('admin.properties.show', $workOrder->property) }}"
                       class="text-indigo-600 hover:underline text-xs">
                        View Property
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection