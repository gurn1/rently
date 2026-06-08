@extends('layouts.portal')

@section('title', 'Edit Lease')

@section('content')
    <div class="max-w-2xl mx-auto">
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

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                {{-- Property (read only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property</label>
                    <input type="text" value="{{ $lease->property->title }}" disabled
                           class="w-full border-gray-300 rounded shadow-sm bg-gray-50 text-gray-500">
                </div>

                {{-- Tenant (read only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                    <input type="text" value="{{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}" disabled
                           class="w-full border-gray-300 rounded shadow-sm bg-gray-50 text-gray-500">
                </div>

                {{-- Rent --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Rent (£)</label>
                    <input type="number" name="rent_amount" value="{{ old('rent_amount', $lease->rent_amount) }}" step="0.01"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('rent_amount') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $lease->start_date) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('start_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $lease->end_date) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('end_date') <p class="error-field-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['pending', 'active', 'ended', 'terminated'] as $status)
                            <option value="{{ $status }}" {{ old('status', $lease->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                {{-- Termination notes --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Termination Notes
                        <span class="text-gray-400 font-normal">(only required if terminating)</span>
                    </label>
                    <textarea name="termination_notes" rows="3"
                              class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('termination_notes', $lease->termination_notes) }}</textarea>
                    @error('termination_notes') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('manager.leases.show', $lease) }}"
                   class="px-6 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection