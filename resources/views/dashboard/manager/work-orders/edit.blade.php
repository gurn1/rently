@extends('layouts.portal')

@section('title', 'Edit Work Order')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Work Order</h1>
            <a href="{{ route('manager.work-orders.show', $workOrder) }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to work order
            </a>
        </div>

        <form method="POST" action="{{ route('manager.work-orders.update', $workOrder) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $workOrder->title) }}"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $workOrder->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                <option value="{{ $priority }}" {{ old('priority', $workOrder->priority) === $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                        @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(['open', 'in_progress', 'pending_review', 'resolved', 'closed'] as $status)
                                <option value="{{ $status }}" {{ old('status', $workOrder->status) === $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Assign To
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <select name="assigned_to"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Unassigned</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('assigned_to', $workOrder->assigned_to) == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->first_name }} {{ $tenant->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('manager.work-orders.show', $workOrder) }}"
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