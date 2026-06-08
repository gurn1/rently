@extends('layouts.portal')

@section('title', 'New Work Order')

@section('content')
    <div>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Submit Work Order</h1>
            <a href="{{ route('tenant.work-orders.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to work orders
            </a>
        </div>

        <form method="POST" action="{{ route('tenant.work-orders.store') }}" class="space-y-6">
            @csrf

            <div class="panel">

                <div class="input-container">
                    <x-input-label>Property</x-input-label>
                    <x-select
                        name="property_id"
                        placeholder="Select a property"
                        :selected="old('property_id')"
                        :options="$leases->mapWithKeys(fn($l) => [$l->property->id => $l->property->title])->toArray()"
                    />
                    @error('property_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Title</x-input-label>
                    <x-text-input name="title" value="{{ old('title') }}"
                        placeholder="e.g. Boiler not working"
                    />
                    @error('title') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container leading-none">
                    <x-input-label>Description</x-input-label>
                    <x-textarea name="description" rows="4"
                              placeholder="Please describe the issue in as much detail as possible..."
                    >{{ old('description') }}</x-textarea>
                    @error('description') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label>Priority</x-input-label>
                    <x-select
                        name="priority"
                        :selected="old('priority')"
                        :options="collect(['low', 'medium', 'high', 'urgent'])->mapWithKeys(fn($p) => [$p => ucfirst($p)])->toArray()"
                    />
                    @error('priority') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('tenant.work-orders.index') }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Submit Request
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection