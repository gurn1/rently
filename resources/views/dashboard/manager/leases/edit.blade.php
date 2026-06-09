@extends('layouts.portal')

@section('title', 'Edit Lease')

@section('content')
    <div>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Lease</h1>
            <a href="{{ route('manager.leases.show', $lease) }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to lease
            </a>
        </div>

        <form method="POST" action="{{ route('manager.leases.update', $lease) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="panel">

                {{-- Property (read only) --}}
                <div class="input-container">
                    <x-input-label>Property</x-input-label>
                    <x-text-input type="text" value="{{ $lease->property->title }}" disabled/>
                </div>

                {{-- Tenant (read only) --}}
                <div class="input-container">
                    <x-input-label>Tenant</x-input-label>
                    <x-text-input type="text" value="{{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}" disabled/>
                </div>

                {{-- Rent --}}
                <div class="input-container">
                    <x-input-label>Monthly Rent (£)</x-input-label>
                    <x-text-input type="number" name="rent_amount" value="{{ old('rent_amount', $lease->rent_amount) }}" step="0.01"/>
                    @error('rent_amount') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4 input-container">
                    <div>
                        <x-input-label>Start Date</x-input-label>
                        <x-text-input type="date" name="start_date" value="{{ old('start_date', $lease->start_date) }}"/>
                        @error('start_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>End Date</x-input-label>
                        <x-text-input type="date" name="end_date" value="{{ old('end_date', $lease->end_date) }}"/>
                        @error('end_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="input-container">
                    <x-input-label>Status</x-input-label>
                    <x-select
                        name="status"
                        :selected="old('status')"
                        :options="collect(['pending', 'active', 'ended', 'terminated'])->mapWithKeys(fn($s) => [$s => ucfirst($s)])->toArray()"
                    />
                    @error('status') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                {{-- Termination notes --}}
                <div>
                    <x-input-label>Termination Notes <span class="text-gray-400 font-normal">(only required if terminating)</span></x-input-label>
                    <x-textarea name="termination_notes" rows="3">
                        {{ old('termination_notes', $lease->termination_notes) }}
                    </x-textarea>
                    @error('termination_notes') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('manager.leases.show', $lease) }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Save Changes
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection