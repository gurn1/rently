@extends('layouts.portal')

@section('title', 'Payment Details')

@section('content')
    <div>
        <div class="mb-8">
            <a href="{{ route('manager.payments.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to payments
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Payment Details</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="panel">
                <div class="mb-2">
                    <p class="text-gray-400">Tenant</p>
                    <p class="font-medium">{{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-400">Amount</p>
                    <p class="font-medium text-lg">£{{ number_format($payment->amount, 2) }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-400">Due Date</p>
                    <p class="font-medium">{{ $payment->due_date?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div class="mb-2">
                    <p class="text-gray-400">Payment Method</p>
                    <p class="font-medium capitalize">{{ $payment->payment_method }}</p>
                </div>
                <div class="mb-2">
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
                            <x-select
                                name="status"
                                :selected="old('status', $payment->status)"
                                :options="collect(['pending', 'paid', 'failed', 'refunded'])->mapWithKeys(fn($s) => [$s => ucfirst($s)])->toArray()"
                            />
                            <x-textarea name="notes" placeholder="Add a note (optional)">{{ $payment->notes }}</x-textarea>
                            <x-primary-button>
                                Update Status
                            </x-primary-button>
                        </form>
                    </div>
                @endif

                @if($payment->paid_at)
                    <div class="mb-2">
                        <p class="text-gray-400">Paid At</p>
                        <p class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
                @if($payment->stripe_payment_intent_id)
                    <div class="mb-2">
                        <p class="text-gray-400">Stripe Reference</p>
                        <p class="font-mono">{{ $payment->stripe_payment_intent_id }}</p>
                    </div>
                @endif
                @if($payment->notes)
                    <div>
                        <p class="text-gray-400">Notes</p>
                        <p class="font-medium">{{ $payment->notes }}</p>
                    </div>
                @endif

                @if($payment->status === 'pending' && $payment->payment_method === 'manual')
                    <div class="pt-4 border-t">
                        <form method="POST" action="{{ route('manager.payments.mark-paid', $payment) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition text-sm">
                                Mark as Paid
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="panel">
                {{-- {{ dd($payment->lease->property) }} --}}
                <div>
                    <p class="text-gray-400">Property</p>
                    <p class="font-medium">{{ $payment->lease->property->title }}</p>
                </div>

                <div>
                    <p class="text-gray-400">Address</p>
                    <p class="font-medium">{{ $payment->lease->property->address }}</p>
                </div>
            </div>
        </div>

    </div>
@endsection