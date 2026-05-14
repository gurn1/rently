@extends('layouts.portal')

@section('title', 'Payments')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">All Payments</h1>
        <p class="text-gray-500 mt-1">{{ $payments->total() }} payment records</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Total Collected</p>
            <p class="text-3xl font-bold text-green-600">£{{ number_format($stats['total_paid'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-500">£{{ number_format($stats['total_pending'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 mb-1">Failed Payments</p>
            <p class="text-3xl font-bold text-red-500">{{ $stats['total_failed'] }}</p>
        </div>
    </div>

    @if($payments->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No payments found.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Tenant</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Property</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Amount</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Due Date</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Method</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $payment->tenant->first_name }} {{ $payment->tenant->last_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $payment->lease->property->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                £{{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $payment->due_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 capitalize">
                                {{ $payment->payment_method }}
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
                                <a href="{{ route('admin.payments.show', $payment) }}"
                                   class="text-indigo-600 hover:underline">View</a>
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