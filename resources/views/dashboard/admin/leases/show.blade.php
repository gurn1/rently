@extends('layouts.portal')

@section('title', 'Lease Details')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('admin.leases.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to leases
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">
                Lease — {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
            </h1>
            <p class="text-gray-500">{{ $lease->property->title }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.leases.edit', $lease) }}"
               class="border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition text-sm">
                Edit Lease
            </a>
            <form method="POST"
                  action="{{ route('admin.leases.destroy', $lease) }}"
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition text-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            {{-- Lease details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Lease Details</h2>
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
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-gray-400 text-sm mb-2">Termination Notes</p>
                        <p class="text-sm text-gray-700">{{ $lease->termination_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Documents</h2>
                @forelse($lease->documents as $document)
                    <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                        <div>
                            <p class="font-medium text-gray-900">{{ $document->title }}</p>
                            <p class="text-gray-400 text-xs capitalize">
                                {{ str_replace('_', ' ', $document->document_type) }}
                            </p>
                        </div>
                        @if($document->requires_signature)
                            <span class="text-xs px-2 py-1 rounded
                                {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $document->is_signed ? 'Signed' : 'Awaiting Signature' }}
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No documents attached.</p>
                @endforelse
            </div>

            {{-- Work Orders --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Work Orders</h2>
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
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Tenant</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">
                        {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                    </p>
                    <p class="text-gray-500">{{ $lease->tenant->email }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Property</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">{{ $lease->property->title }}</p>
                    <p class="text-gray-500">{{ $lease->property->address }}</p>
                    @if($lease->property->propertyManager)
                        <p class="text-gray-500">
                            Manager: {{ $lease->property->propertyManager->first_name }}
                            {{ $lease->property->propertyManager->last_name }}
                        </p>
                    @endif
                    <a href="{{ route('admin.properties.show', $lease->property) }}"
                       class="text-indigo-600 hover:underline text-xs">
                        View Property
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection