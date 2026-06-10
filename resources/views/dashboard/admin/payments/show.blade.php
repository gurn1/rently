@extends('layouts.portal')

@section('title', 'Payment Details')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.payments.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to payments
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Payment Details</h1>
        </div>

        <div class="panel">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Tenant</p>
                    <p class="font-medium">{{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Property</p>
                    <p class="font-medium">{{ $payment->lease->property->title }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Amount</p>
                    <p class="font-medium text-lg">£{{ number_format($payment->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Due Date</p>
                    <p class="font-medium">{{ $payment->due_date?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Payment Method</p>
                    <p class="font-medium capitalize">{{ $payment->payment_method }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Status</p>
                    <span class="text-xs px-2 py-1 rounded capitalize
                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' :
                           ($payment->status === 'failed' ? 'bg-red-100 text-red-700' :
                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           'bg-gray-100 text-gray-600')) }}">
                        {{ $payment->status }}
                    </span>
                </div>

                {{-- Status update --}}
                @if($payment->status !== 'paid')
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <h3 class="font-medium text-gray-700 mb-3 text-sm">Update Status</h3>
                        <form method="POST"
                            action="{{ route(auth()->user()->routePrefix() . '.payments.update-status', $payment) }}"
                            class="space-y-3">
                            @csrf
                            <select name="status"
                                    class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                                    <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <textarea name="notes" rows="2" placeholder="Add a note (optional)"
                                    class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ $payment->notes }}</textarea>
                            <button type="submit"
                                    class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition text-sm">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endif

                @if($payment->paid_at)
                    <div>
                        <p class="text-gray-400">Paid At</p>
                        <p class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
                @if($payment->stripe_payment_intent_id)
                    <div class="col-span-2">
                        <p class="text-gray-400">Stripe Reference</p>
                        <p class="font-mono text-xs text-gray-600">{{ $payment->stripe_payment_intent_id }}</p>
                    </div>
                @endif
                @if($payment->notes)
                    <div class="col-span-2">
                        <p class="text-gray-400">Notes</p>
                        <p class="font-medium">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection