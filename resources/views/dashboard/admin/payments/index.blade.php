@extends('layouts.portal')

@section('title', 'Payments')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
            <p class="text-gray-500 mt-1">{{ $payments->total() }} payment records</p>
        </div>
        <a href="{{ route('admin.payments.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + Request Payment
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Total Collected</p>
            <p class="text-3xl font-bold text-green-600">£{{ number_format($stats['total_paid'], 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-500">£{{ number_format($stats['total_pending'], 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-sm text-gray-500 mb-1">Failed Payments</p>
            <p class="text-3xl font-bold text-red-500">{{ $stats['total_failed'] }}</p>
        </div>
    </div>

    @if($payments->isEmpty())
        <div class="panel">
            <p class="text-xl">No payments found.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td class=" font-medium text-gray-900">
                                {{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}
                            </td>
                            <td >
                                {{ $payment->lease->property->title }}
                            </td>
                            <td >
                                £{{ number_format($payment->amount, 2) }}
                            </td>
                            <td >
                                {{ $payment->due_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="capitalize">
                                {{ $payment->payment_method }}
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
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="text-indigo-600 hover:underline">View</a>
                                @if($payment->status === 'pending' && $payment->payment_method === 'manual')
                                    <form method="POST"
                                            action="{{ route('manager.payments.mark-paid', $payment) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-green-600 hover:underline">
                                            Mark Paid
                                        </button>
                                    </form>
                                @endif
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