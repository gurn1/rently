@extends('layouts.portal')

@section('title', 'Payments')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        <p class="text-gray-500 mt-1">Your rent payment history.</p>
    </div>

    {{-- Failed payment warning --}}
    @if($failedPayments > 0)
        <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-4 rounded-lg mb-8 flex justify-between items-center">
            <div>
                <p class="font-semibold">You have {{ $failedPayments }} failed payment(s).</p>
                <p class="text-sm mt-1">Please update your payment details to avoid any issues with your tenancy.</p>
            </div>
        </div>
    @endif

    @if($payments->isEmpty())
        <div class="panel">
            <p class="text-xl">No payments yet.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td class="text-gray-900">
                                {{ $payment->lease->property->title }}
                            </td>
                            <td>
                                £{{ number_format($payment->amount, 2) }}
                            </td>
                            <td>
                                {{ $payment->due_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td>
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' :
                                       ($payment->status === 'failed' ? 'bg-red-100 text-red-700' :
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-gray-100 text-gray-600')) }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('tenant.payments.show', $payment) }}"
                                       class="text-indigo-600 hover:underline">View</a>
                                    @if($payment->status === 'pending' && $payment->payment_method === 'stripe')
                                        <a href="{{ route('tenant.payments.checkout', $payment) }}"
                                           class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 transition text-xs">
                                            Pay Now
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
@endsection