@extends('layouts.portal')

@section('title', 'Edit Work Order')

@section('content')
    <div>
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

            <div class="panel">

                <div class="input-container">
                    <x-input-label>Property</x-input-label>
                    <x-select
                        name="property_id"
                        placeholder="Select a property"
                        :selected="old('property_id')"
                        :options="$properties->pluck('title', 'id')->toArray()"
                    />
                    @error('property_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Title</x-input-label>
                    <x-text-input type="text" name="title" value="{{ old('title') }}"/>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label>Description</x-input-label>
                    <x-textarea name="description" rows="4">{{ old('description') }}</x-textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label>Priority</x-input-label>
                    <x-select
                        name="priority"
                        :selected="old('priority')"
                        :options="collect(['low', 'medium', 'high', 'urgent'])->mapWithKeys(fn($p) => [$p => ucfirst($p)])->toArray()"
                    />
                    @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('manager.work-orders.show', $workOrder) }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Save Changes
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection