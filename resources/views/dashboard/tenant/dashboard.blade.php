@extends('layouts.portal')

@section('title', 'My Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->first_name }}
        </h1>
        <p class="text-gray-500 mt-1">Here's an overview of your tenancy.</p>
    </div>

    @if($failedPayments > 0)
        <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-4 rounded-lg mb-8 flex justify-between items-center">
            <div>
                <p class="font-semibold">You have {{ $failedPayments }} failed payment(s).</p>
                <p class="text-sm mt-1">Please update your payment details as soon as possible.</p>
            </div>
            <a href="{{ route('tenant.payments.index') }}"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-sm flex-shrink-0">
                View Payments
            </a>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Lease Status --}}
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Lease Status</p>
            @if($activeLease)
                <p class="text-lg font-bold text-green-600">Active</p>
                <p class="text-xs text-gray-400 mt-1">{{ $activeLease->property->title }}</p>
            @else
                <p class="text-lg font-bold text-gray-400">No Active Lease</p>
            @endif
        </div>

        {{-- Unread messages --}}
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Unread Messages</p>
            <p class="text-3xl font-bold {{ $unreadMessages > 0 ? 'text-indigo-600' : 'text-gray-400' }}">
                {{ $unreadMessages }}
            </p>
        </div>

        {{-- Open work orders --}}
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Open Work Orders</p>
            <p class="text-3xl font-bold {{ $openWorkOrders > 0 ? 'text-orange-500' : 'text-gray-400' }}">
                {{ $openWorkOrders }}
            </p>
        </div>

        {{-- Documents to sign --}}
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Documents to Sign</p>
            <p class="text-3xl font-bold {{ $pendingDocuments > 0 ? 'text-red-500' : 'text-gray-400' }}">
                {{ $pendingDocuments }}
            </p>
        </div>
    </div>

    <div class="flex flex-row gap-4">

        <div class="flex-1">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Active lease --}}
                <div class="panel">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="panel-title mb-0">My Lease</h2>
                        <a href="{{ route('tenant.leases.index') }}"
                        class="text-xs text-indigo-600 hover:underline">View all</a>
                    </div>
                    @if($activeLease)
                        <div class="text-sm space-y-2">
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-400">Property</span>
                                <span class="font-medium">{{ $activeLease->property->title }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-400">Monthly Rent</span>
                                <span class="font-medium">£{{ number_format($activeLease->rent_amount, 0) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-400">Start Date</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($activeLease->start_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">End Date</span>
                                <span class="font-medium">
                                    {{ $activeLease->end_date ? \Carbon\Carbon::parse($activeLease->end_date)->format('d/m/Y') : 'Ongoing' }}
                                </span>
                            </div>
                        </div>

                        <x-outline-button href="{{ route('tenant.leases.show', $activeLease) }}" class="w-full text-indigo-600 border-indigo-600 mt-3">
                            View Lease Details
                        </x-outline-button>
                    @else
                        <p class="text-gray-400 text-sm">No active lease found.</p>
                    @endif
                </div>

                {{-- Recent payments --}}
                <div class="panel">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="panel-title mb-0">Payment History</h2>
                        <a href="{{ route('tenant.payments.index') }}"
                        class="text-xs text-indigo-600 hover:underline">View all</a>
                    </div>
                    <div>

                        @if ($payments->isEmpty())
                            <p class="text-gray-400 text-sm">No payments yet.</p>
                        @else

                            <table class="data-table small">
                                <thead>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th class="text-right">Status</th>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td class="text-gray-900 font-medium">
                                                <a href="{{ route('tenant.payments.show', $payment) }}">{{ $payment->due_date?->format('d/m/Y') ?? '—' }}</a></td>
                                            <td>
                                                <a href="{{ route('tenant.payments.show', $payment) }}">£{{ number_format($payment->amount, 2) }}</a>
                                            </td>
                                            <td class="text-right">
                                                <span class="text-xs px-2 py-1 rounded capitalize
                                                    {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' :
                                                    ($payment->status === 'failed' ? 'bg-red-100 text-red-700' :
                                                    ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                                    'bg-gray-100 text-gray-600')) }}">
                                                    {{ $payment->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @endif
                    </div>
                </div>

                {{-- Documents awaiting signature --}}
                <div class="panel">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="panel-title mb-0">Documents</h2>
                        <a href="{{ route('tenant.documents.index') }}"
                        class="text-xs text-indigo-600 hover:underline">View all</a>
                    </div>
                    <div>

                        @if ($documents->isEmpty())
                            <p class="text-gray-400 text-sm">No documents yet.</p>
                        @else

                            <table class="data-table small">
                                <thead>
                                    <th>Name</th>
                                    <th class="text-right">Status</th>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>
                                                <a href="{{ route('tenant.documents.show', $document) }}"> 
                                                    <span class="font-medium">{{ $document->title }}</span>
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                @if($document->requires_signature && !$document->is_signed)
                                                    <span class="text-xs px-2 py-0.5 rounded bg-yellow-100 text-yellow-700">Sign</span>
                                                @elseif($document->is_signed)
                                                    <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700">Signed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @endif
                    </div>
                </div>

                {{-- Recent work orders --}}
                <div class="panel">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="panel-title mb-0">Work Orders</h2>
                        <a href="{{ route('tenant.work-orders.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
                    </div>

                    <div>
                        @if ($workOrders->isEmpty())
                            <p class="text-gray-400 text-sm">No work orders yet.</p>
                        @else
                            <table class="data-table small">
                                <thead>
                                    <th>Issue</th>
                                    <th class="text-right">Status</th>
                                </thead>
                                <tbody>
                                    @foreach($workOrders as $workOrder)
                                        <tr>
                                            <td>
                                                <a href="{{ route('tenant.work-orders.show', $workOrder) }}">
                                                    <span class="font-medium">{{ $workOrder->title }}</span>
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <span class="text-xs px-2 py-0.5 rounded capitalize
                                                    {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                                                    ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                                                    'bg-yellow-100 text-yellow-700') }}">
                                                    {{ str_replace('_', ' ', $workOrder->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <x-outline-button href="{{ route('tenant.work-orders.create') }}" class="w-full text-indigo-600 border-indigo-600 mt-3">
                            Submit a Request
                        </x-outline-button>
                    </div>

                </div>

            </div>

        </div>

        <div class="w-90 shrink-0">
            <div class="panel sticky top-20 bg-mist-200 flex flex-col items-center">
                <div class="w-full bg-gray-900 text-white rounded-xl uppercase py-2 px-4 text-center text-sm font-semibold mb-6">Property Manager</div>

                <div class="w-20 h-20 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    @if($propertyManager['profile_image'])
                        <img src="{{ $propertyManager['profile_image'] }}"
                                alt="{{ $propertyManager['first_name'] }}"
                                class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-indigo-600">
                            {{ strtoupper(substr($propertyManager['first_name'], 0, 1)) }}{{ strtoupper(substr($propertyManager['last_name'], 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="mt-6 font-medium text-xl">
                    {{ $propertyManager['first_name'] }} {{ $propertyManager['last_name'] }}
                </div>

                <div class="px-4 mt-6">
                    <x-outline-button href="mailto:{{ $propertyManager['email'] }}" class="w-full mb-2">
                        <span class="material-symbols-outlined mr-2">mail</span> Email
                    </x-outline-button>
                    <x-outline-button href="tel:{{ $propertyManager['phone'] }}" class="w-full mb-2">
                        <span class="material-symbols-outlined mr-2">phone_enabled</span> Phone
                    </x-outline-button>
                    <x-outline-button href="{{ route('tenant.messages.index') }}" class="w-full">
                        <span class="material-symbols-outlined mr-2">chat_bubble</span> Message
                    </x-outline-button>
                </div>
            </div>
        </div>
    </div>
@endsection