@extends('layouts.portal')

@section('title', 'Payment Successful')

@section('content')
    <div class="max-w-lg mx-auto text-center py-16">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful</h1>
        <p class="text-gray-500 mb-8">
            Your payment of £{{ number_format($payment->amount, 2) }} has been received.
        </p>
        <a href="{{ route('tenant.payments.index') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">
            View Payments
        </a>
    </div>
@endsection