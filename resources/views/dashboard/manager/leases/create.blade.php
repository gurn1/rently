@extends('layouts.portal')

@section('title', 'Create Lease')

@section('content')
    <div>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create New Lease</h1>
            <a href="{{ route('manager.leases.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to leases
            </a>
        </div>

        <form method="POST" action="{{ route('manager.leases.store') }}" class="space-y-6">
            @csrf

            <div class="panel">

                <div class="input-container">
                    <x-input-label>Property</x-input-label>
                    <x-select
                        name="property_id"
                        placeholder="Select a property"
                        :selected="old('property_id')"
                        :options="$propertyOptions"
                    />
                    @error('property_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Tenant</x-input-label>
                    <x-select
                        name="tenant_id"
                        placeholder="Select a tenant"
                        :selected="old('tenant_id')"
                        :options="$tenants->mapWithKeys(fn($t) => [$t->id => $t->first_name . ' ' . $t->last_name])->toArray()"
                    />
                    @error('tenant_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Monthly Rent (£)</x-input-label>
                    <x-text-input type="number" name="rent_amount" value="{{ old('rent_amount') }}" step="0.01"/>
                    @error('rent_amount') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 input-container">
                    <div>
                        <x-input-label>Start Date</x-input-label>
                        <x-text-input type="date" name="start_date" value="{{ old('start_date') }}"/>
                        @error('start_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>End Date</x-input-label>
                        <x-text-input type="date" name="end_date" value="{{ old('end_date') }}"/>
                        @error('end_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <x-input-label>Status</x-input-label>
                    <x-select
                        name="status"
                        :selected="old('status')"
                        :options="collect(['pending', 'active', 'ended', 'terminated'])->mapWithKeys(fn($s) => [$s => ucfirst($s)])->toArray()"
                    />
                    @error('status') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('manager.leases.index') }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Create Lease
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection