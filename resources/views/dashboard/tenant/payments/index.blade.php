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
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No payments yet.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Property</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Amount</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Due Date</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $payment->lease->property->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                £{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $payment->due_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' :
                                       ($payment->status === 'failed' ? 'bg-red-100 text-red-700' :
                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-gray-100 text-gray-600')) }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
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