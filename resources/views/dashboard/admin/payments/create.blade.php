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

            <div class="panel">

                <div class="input-container">
                    <x-input-label>Lease</x-input-label>
                    <x-select
                        name="lease_id"
                        placeholder="Select a lease"
                        :selected="old('lease_id')"
                        :options="$leases->mapWithKeys(fn($l) => [$l->id => $l->property->title . ' — ' . $l->tenant->first_name . ' ' . $l->tenant->last_name])->toArray()"
                    />
                    @error('lease_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Amount (£)</x-input-label>
                    <x-text-input type="number" name="amount" value="{{ old('amount') }}" step="0.01"/>
                    @error('amount') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Due Date</x-input-label>
                    <x-text-input type="date" name="due_date" value="{{ old('due_date') }}"/>
                    @error('due_date') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Payment Method</x-input-label>
                    <x-select
                        name="payment_method"
                        :selected="old('payment_method')"
                        :options="['stripe' => 'Stripe (Online Payment)', 'manual' => 'Manual (Bank Transfer / Cash)']"
                    />
                    <p class="text-xs text-gray-400 mt-1">
                        This determines how the tenant will pay their monthly rent.
                    </p>
                    @error('payment_method') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label>Notes <span class="text-gray-400 font-normal">(optional)</span></x-input-label>
                    <x-textarea name="notes" rows="3">{{ old('notes') }}</x-textarea>
                    @error('notes') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('admin.payments.index') }}">
                    Cancel
                </x-outline-button>
                <x-primary-button type="submit">
                    Request Payment
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection