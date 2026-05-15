@extends('layouts.portal')

@section('title', 'Request Payment')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Request Payment</h1>
            <a href="{{ route('admin.payments.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to payments
            </a>
        </div>

        <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lease</label>
                    <select name="lease_id"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a lease</option>
                        @foreach($leases as $lease)
                            <option value="{{ $lease->id }}" {{ old('lease_id') == $lease->id ? 'selected' : '' }}>
                                {{ $lease->property->title }} — {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lease_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (£)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="stripe" {{ old('payment_method') === 'stripe' ? 'selected' : '' }}>
                            Stripe (Online Payment)
                        </option>
                        <option value="manual" {{ old('payment_method') === 'manual' ? 'selected' : '' }}>
                            Manual (Bank Transfer / Cash)
                        </option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">
                        This determines how the tenant will pay their monthly rent.
                    </p>
                    @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Notes
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea name="notes" rows="3"
                              class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.payments.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Request Payment
                </button>
            </div>
        </form>
    </div>
@endsection