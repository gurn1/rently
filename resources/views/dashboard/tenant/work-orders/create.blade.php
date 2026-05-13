@extends('layouts.portal')

@section('title', 'New Work Order')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Submit Work Order</h1>
            <a href="{{ route('tenant.work-orders.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to work orders
            </a>
        </div>

        <form method="POST" action="{{ route('tenant.work-orders.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property</label>
                    <select name="property_id"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a property</option>
                        @foreach($leases as $lease)
                            <option value="{{ $lease->property->id }}" {{ old('property_id') == $lease->property->id ? 'selected' : '' }}>
                                {{ $lease->property->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="e.g. Boiler not working"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              placeholder="Please describe the issue in as much detail as possible..."
                              class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                            <option value="{{ $priority }}" {{ old('priority') === $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('tenant.work-orders.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
@endsection