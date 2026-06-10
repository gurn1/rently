@extends('layouts.portal')

@section('title', $property->title)

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('admin.properties.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to properties
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $property->title }}</h1>
            <p class="text-gray-500">{{ $property->address }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.properties.edit', $property) }}"
               class="border border-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-50 transition text-sm">
                Edit Property
            </a>
            <form method="POST"
                  action="{{ route('admin.properties.destroy', $property) }}"
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition text-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Property details --}}
            <div class="panel">
                <h2 class="panel-title">Property Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Type</p>
                        <p class="font-medium capitalize">{{ str_replace('_', ' ', $property->property_type) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Price</p>
                        <p class="font-medium">£{{ number_format($property->price, 0) }}/mo</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Bedrooms</p>
                        <p class="font-medium">{{ $property->bedrooms }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Bathrooms</p>
                        <p class="font-medium">{{ $property->bathrooms }}</p>
                    </div>
                    @if($property->size)
                        <div>
                            <p class="text-gray-400">Size</p>
                            <p class="font-medium">{{ $property->size }} sq ft</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-400">Status</p>
                        <span class="text-xs px-2 py-1 rounded capitalize
                            {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ str_replace('_', ' ', $property->availability_status) }}
                        </span>
                    </div>
                </div>

                @if($property->key_features)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-gray-400 text-sm mb-2">Key Features</p>
                        <p class="text-sm text-gray-700">{!! nl2br(e($property->key_features)) !!}</p>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-gray-400 text-sm mb-2">Description</p>
                    <p class="text-sm text-gray-700">{!! nl2br(e($property->description)) !!}</p>
                </div>
            </div>

            {{-- Amenities --}}
            @if($property->amenities->count() > 0)
                <div class="panel">
                    <h2 class="panel-title">Amenities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($property->amenities as $amenity)
                            <span class="bg-indigo-50 text-indigo-700 text-sm px-3 py-2 rounded">
                                {{ $amenity->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Leases --}}
            <div class="panel">
                <h2 class="panel-title">Leases</h2>
                @forelse($property->leases as $lease)
                    <div class="py-3 px-4 text-sm rounded-lg border-gray-100 border">
                        <a href="{{ route('admin.leases.show', $lease) }}" class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                                </p>
                                <p class="text-gray-400 text-xs mt-1">
                                    {{ $lease->start_date }} — {{ $lease->end_date ?? 'Ongoing' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-indigo-600">£{{ number_format($lease->rent_amount, 0) }}/mo</p>
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $lease->status }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-sm">No leases for this property yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="panel">
                <h2 class="panel-title">Quick Links</h2>
                <div class="flex flex-col gap-3 text-sm">
                    <a href="#" class="text-indigo-600 hover:underline">View Documents</a>
                    <a href="#" class="text-indigo-600 hover:underline">View Work Orders</a>
                    <a href="#" class="text-indigo-600 hover:underline">View Messages</a>
                </div>
            </div>
        </div>
    </div>
@endsection