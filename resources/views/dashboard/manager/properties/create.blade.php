@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.portal')

@section('title', 'Add Property')

@section('content')
    <div class="">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Add New Property</h1>
            <a href="{{ route('manager.dashboard') }}" 
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to dashboard
            </a>
        </div>

        <form method="POST" action="{{ route('manager.properties.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="panel">
                <h2 class="panel-title">Property Details</h2>

                {{-- Title --}}
                <div class="input-container">
                    <x-input-label>Title</x-input-label>
                    <x-text-input type="text" name="title" value="{{ old('title') }}"/>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Key Features --}}
                <div class="input-container">
                    <x-input-label>Key Features</x-input-label>
                    <x-textarea name="key_features">{{ old('key_features') }}</x-textarea>
                    @error('key_features') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="input-container">
                    <x-input-label>Description</x-input-label>
                    <x-textarea name="description" rows="5">{{ old('description') }}</x-textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Address --}}
                <div class="input-container">
                    <x-input-label>Address</x-input-label>
                    <x-text-input type="text" name="address" value="{{ old('address') }}"/>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Lat / Long --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label>Latitude</x-input-label>
                        <x-text-input type="text" name="latitude" value="{{ old('latitude') }}"/>
                        @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Longitude</x-input-label>
                        <x-text-input type="text" name="longitude" value="{{ old('longitude') }}"/>
                        @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="panel">
                <h2 class="panel-title">Property Specifications</h2>

                {{-- Price --}}
                <div class="input-container">
                    <x-input-label>Monthly Rent (£)</x-input-label>
                    <x-text-input type="number" name="price" value="{{ old('price') }}" step="0.01"/>
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Property type / Status --}}
                <div class="grid grid-cols-2 gap-4 input-container">
                    <div>
                        <x-input-label>Property Type</x-input-label>
                        <x-select 
                            name="property_type"
                            :selected="old('property_type')"
                            :options="['house' => 'House', 'apartment' => 'Apartment', 'studio' => 'Studio', 'commercial' => 'Commercial']"
                        />
                        @error('property_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Availability</x-input-label>
                        <x-select 
                            name="availability_status"
                            :selected="old('availability_status')"
                            :options="['available' => 'Available', 'occupied' => 'Occupied', 'under_maintenance' => 'Under Maintenance']"
                        />
                        @error('availability_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Bedrooms / Bathrooms / Size --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label>Bedrooms</x-input-label>
                        <x-text-input type="number" name="bedrooms" value="{{ old('bedrooms') }}" min="0"/>
                        @error('bedrooms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Bathrooms</x-input-label>
                        <x-text-input type="number" name="bathrooms" value="{{ old('bathrooms') }}" min="0"/>
                        @error('bathrooms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <x-input-label>Size (sq ft)</x-input-label>
                        <x-text-input type="number" name="size" value="{{ old('size') }}" min="0"/>
                        @error('size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Images --}}
            @include('dashboard.partials.property-images')

            {{-- Amenities --}}
            <div class="panel">
                <h2 class="panel-title">Amenities</h2>
                <div class="max-w-[50%]">
                    <x-checkbox-group
                        name="amenities"
                        :options="$amenities->pluck('name', 'id')->toArray()"
                        :selected="old('amenities', [])" 
                    />
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('manager.properties.index') }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Create Property
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection