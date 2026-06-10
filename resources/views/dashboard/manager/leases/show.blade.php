@extends('layouts.portal')

@section('title', 'Lease Details')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('manager.leases.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to leases
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                Lease — {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
            </h1>
            <p class="text-gray-500">{{ $lease->property->title }}</p>
        </div>
        <a href="{{ route('manager.leases.edit', $lease) }}"
           class="border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition text-sm">
            Edit Lease
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Lease details --}}
            <div class="panel">
                <h2 class="panel-title">Lease Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Status</p>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                               ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               'bg-red-100 text-red-700') }}">
                            {{ $lease->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400">Monthly Rent</p>
                        <p class="font-medium">£{{ number_format($lease->rent_amount, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Start Date</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">End Date</p>
                        <p class="font-medium">
                            {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'Ongoing' }}
                        </p>
                    </div>
                    @if($lease->terminated_at)
                        <div>
                            <p class="text-gray-400">Terminated At</p>
                            <p class="font-medium">{{ $lease->terminated_at->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>

                @if($lease->termination_notes)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-gray-400 text-sm mb-2">Termination Notes</p>
                        <p class="text-sm text-gray-700">{{ $lease->termination_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Documents --}}
            <div class="panel">
                <h2 class="panel-title">Documents</h2>
                @forelse($lease->documents as $document)
                    <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                        <div>
                            <p class="font-medium text-gray-900">{{ $document->title }}</p>
                            <p class="text-gray-400 text-xs capitalize">{{ str_replace('_', ' ', $document->document_type) }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($document->requires_signature)
                                <span class="text-xs px-2 py-1 rounded
                                    {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $document->is_signed ? 'Signed' : 'Awaiting Signature' }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No documents attached.</p>
                @endforelse
            </div>

            {{-- Work Orders --}}
            <div class="panel">
                <h2 class="panel-title">Work Orders</h2>
                @forelse($lease->workOrders as $workOrder)
                    <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                        <p class="font-medium text-gray-900">{{ $workOrder->title }}</p>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                               ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                               'bg-yellow-100 text-yellow-700') }}">
                            {{ str_replace('_', ' ', $workOrder->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No work orders.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="panel">
                <h2 class="panel-title">Tenant</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">
                        {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                    </p>
                    <p class="text-gray-500">{{ $lease->tenant->email }}</p>
                </div>
            </div>

            <div class="panel">
                <h2 class="panel-title">Property</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">{{ $lease->property->title }}</p>
                    <p class="text-gray-500">{{ $lease->property->address }}</p>
                    <a href="{{ route('manager.properties.show', $lease->property) }}"
                       class="text-indigo-600 hover:underline text-xs">
                        View Property
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection