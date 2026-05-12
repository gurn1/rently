@extends('layouts.portal')

@section('title', 'Lease Details')

@section('content')
    <div class="mb-8">
        <a href="{{ route('tenant.leases.index') }}"
           class="text-sm text-indigo-600 hover:underline">
            &larr; Back to leases
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $lease->property->title }}</h1>
        <p class="text-gray-500">{{ $lease->property->address }}</p>
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
                </div>
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
                        @if($document->requires_signature && !$document->is_signed)
                            <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                                Awaiting Signature
                            </span>
                        @elseif($document->is_signed)
                            <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">
                                Signed
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
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold text-gray-700 border-b pb-2 mb-4">Property Details</h2>
                <div class="text-sm space-y-2">
                    <p class="font-medium text-gray-900">{{ $lease->property->title }}</p>
                    <p class="text-gray-500">{{ $lease->property->address }}</p>
                    <div class="flex gap-4 mt-3 text-gray-600">
                        <span>🛏 {{ $lease->property->bedrooms }} bed</span>
                        <span>🚿 {{ $lease->property->bathrooms }} bath</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection