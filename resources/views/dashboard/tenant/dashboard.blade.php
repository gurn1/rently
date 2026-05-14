@extends('layouts.portal')

@section('title', 'My Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->first_name }}
        </h1>
        <p class="text-gray-500 mt-1">Here's an overview of your tenancy.</p>
    </div>

    @php
        $activeLease = auth()->user()->leases()->where('status', 'active')->with('property')->first();
        $unreadMessages = App\Models\Conversation::where('tenant_id', auth()->id())
            ->with(['messages' => fn($q) => $q->whereNull('read_at')->where('sender_id', '!=', auth()->id())])
            ->get()
            ->sum(fn($c) => $c->messages->count());
        $openWorkOrders = App\Models\WorkOrder::where('raised_by', auth()->id())
            ->whereIn('status', ['open', 'in_progress', 'pending_review'])
            ->count();
        $pendingDocuments = App\Models\Document::where('tenant_id', auth()->id())
            ->where('requires_signature', true)
            ->where('is_signed', false)
            ->count();
    @endphp

    @php
        $failedPayments = App\Models\Payment::where('tenant_id', auth()->id())
            ->where('status', 'failed')
            ->count();
    @endphp

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
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Lease Status</p>
            @if($activeLease)
                <p class="text-lg font-bold text-green-600">Active</p>
                <p class="text-xs text-gray-400 mt-1">{{ $activeLease->property->title }}</p>
            @else
                <p class="text-lg font-bold text-gray-400">No Active Lease</p>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Unread Messages</p>
            <p class="text-3xl font-bold {{ $unreadMessages > 0 ? 'text-indigo-600' : 'text-gray-400' }}">
                {{ $unreadMessages }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Open Work Orders</p>
            <p class="text-3xl font-bold {{ $openWorkOrders > 0 ? 'text-orange-500' : 'text-gray-400' }}">
                {{ $openWorkOrders }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Documents to Sign</p>
            <p class="text-3xl font-bold {{ $pendingDocuments > 0 ? 'text-red-500' : 'text-gray-400' }}">
                {{ $pendingDocuments }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Active lease --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">My Lease</h2>
                <a href="{{ route('tenant.leases.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($activeLease)
                <div class="text-sm space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Property</span>
                        <span class="font-medium">{{ $activeLease->property->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Monthly Rent</span>
                        <span class="font-medium">£{{ number_format($activeLease->rent_amount, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
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
                <a href="{{ route('tenant.leases.show', $activeLease) }}"
                   class="mt-4 block text-center border border-indigo-600 text-indigo-600 py-2 rounded hover:bg-indigo-50 transition text-sm">
                    View Lease Details
                </a>
            @else
                <p class="text-gray-400 text-sm">No active lease found.</p>
            @endif
        </div>

        {{-- Documents awaiting signature --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Documents</h2>
                <a href="{{ route('tenant.documents.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse(App\Models\Document::where('tenant_id', auth()->id())->latest()->take(4)->get() as $document)
                <a href="{{ route('tenant.documents.show', $document) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <span class="font-medium">{{ $document->title }}</span>
                    @if($document->requires_signature && !$document->is_signed)
                        <span class="text-xs px-2 py-0.5 rounded bg-yellow-100 text-yellow-700">Sign</span>
                    @elseif($document->is_signed)
                        <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-700">Signed</span>
                    @endif
                </a>
            @empty
                <p class="text-gray-400 text-sm">No documents yet.</p>
            @endforelse
        </div>

        {{-- Recent work orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Work Orders</h2>
                <a href="{{ route('tenant.work-orders.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse(App\Models\WorkOrder::where('raised_by', auth()->id())->latest()->take(4)->get() as $workOrder)
                <a href="{{ route('tenant.work-orders.show', $workOrder) }}"
                   class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <span class="font-medium">{{ $workOrder->title }}</span>
                    <span class="text-xs px-2 py-0.5 rounded capitalize
                        {{ $workOrder->status === 'resolved' ? 'bg-green-100 text-green-700' :
                           ($workOrder->status === 'open' ? 'bg-red-100 text-red-700' :
                           'bg-yellow-100 text-yellow-700') }}">
                        {{ str_replace('_', ' ', $workOrder->status) }}
                    </span>
                </a>
            @empty
                <p class="text-gray-400 text-sm">No work orders yet.</p>
                <a href="{{ route('tenant.work-orders.create') }}"
                   class="mt-3 block text-center border border-indigo-600 text-indigo-600 py-2 rounded hover:bg-indigo-50 transition text-sm">
                    Submit a Request
                </a>
            @endforelse
        </div>

        {{-- Recent messages --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="font-semibold text-gray-700">Messages</h2>
                <a href="{{ route('tenant.messages.index') }}"
                   class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @forelse(App\Models\Conversation::where('tenant_id', auth()->id())->with(['propertyManager', 'messages' => fn($q) => $q->latest()->limit(1)])->latest('last_message_at')->take(3)->get() as $conversation)
                @php $lastMessage = $conversation->messages->first(); @endphp
                <a href="{{ route('tenant.messages.show', $conversation) }}"
                   class="flex justify-between items-start py-2 border-b last:border-0 hover:text-indigo-600 transition text-sm">
                    <div>
                        <p class="font-medium">{{ $conversation->propertyManager->first_name }} {{ $conversation->propertyManager->last_name }}</p>
                        @if($lastMessage)
                            <p class="text-gray-400 text-xs truncate max-w-xs">{{ $lastMessage->body }}</p>
                        @endif
                    </div>
                    @if($lastMessage && !$lastMessage->read_at && $lastMessage->sender_id !== auth()->id())
                        <span class="bg-indigo-600 text-white text-xs rounded-full px-2 py-0.5 flex-shrink-0">New</span>
                    @endif
                </a>
            @empty
                <p class="text-gray-400 text-sm">No messages yet.</p>
            @endforelse
        </div>
    </div>
@endsection