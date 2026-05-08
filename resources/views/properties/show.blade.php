@extends('layouts.public')

@section('title', $property->title)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main content --}}
            <div class="lg:col-span-2">

                {{-- Featured image --}}
                <div class="rounded-lg overflow-hidden bg-gray-200 h-96 mb-6">
                    @if($property->images->where('is_featured', true)->first())
                        <img src="{{ $property->images->where('is_featured', true)->first()->path }}"
                             alt="{{ $property->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            No image available
                        </div>
                    @endif
                </div>

                {{-- Image gallery --}}
                @if($property->images->count() > 1)
                    <div class="grid grid-cols-4 gap-2 mb-8">
                        @foreach($property->images->where('is_featured', false) as $image)
                            <div class="h-24 rounded overflow-hidden bg-gray-200">
                                <img src="{{ $image->path }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Title and price --}}
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $property->title }}</h1>
                    <span class="text-2xl font-bold text-indigo-600">
                        £{{ number_format($property->price, 0) }}<span class="text-sm font-normal text-gray-500">/mo</span>
                    </span>
                </div>

                <p class="text-gray-500 mb-6">{{ $property->address }}</p>

                {{-- Key stats --}}
                <div class="flex gap-6 py-4 border-y mb-8 text-sm text-gray-600">
                    <span>🛏 {{ $property->bedrooms }} Bedrooms</span>
                    <span>🚿 {{ $property->bathrooms }} Bathrooms</span>
                    @if($property->size)
                        <span>📐 {{ $property->size }} sq ft</span>
                    @endif
                    <span class="capitalize">🏠 {{ str_replace('_', ' ', $property->property_type) }}</span>
                </div>

                {{-- Key features --}}
                @if($property->key_features)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Key Features</h2>
                        <div class="prose text-gray-600">
                            {!! nl2br(e($property->key_features)) !!}
                        </div>
                    </div>
                @endif

                {{-- Description --}}
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-3">Description</h2>
                    <div class="prose text-gray-600">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>

                {{-- Amenities --}}
                @if($property->amenities->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-3">Amenities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->amenities as $amenity)
                                <span class="bg-indigo-50 text-indigo-700 text-sm px-3 py-2 rounded">
                                    {{ $amenity->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <div class="mb-4">
                        <span class="text-sm px-3 py-1 rounded-full capitalize
                            {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ str_replace('_', ' ', $property->availability_status) }}
                        </span>
                    </div>

                    <p class="text-3xl font-bold text-indigo-600 mb-1">
                        £{{ number_format($property->price, 0) }}
                    </p>
                    <p class="text-gray-400 text-sm mb-6">per month</p>

                    @if($property->availability_status === 'available')
                        @auth
                            <a href="#" 
                               class="block w-full text-center bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition font-medium">
                                Express Interest
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition font-medium">
                                Login to Apply
                            </a>
                        @endauth
                    @else
                        <button disabled 
                                class="block w-full text-center bg-gray-200 text-gray-500 py-3 rounded font-medium cursor-not-allowed">
                            Not Available
                        </button>
                    @endif

                    <div class="mt-6 pt-6 border-t text-sm text-gray-500">
                        <p class="mb-2">📍 {{ $property->address }}</p>
                        @if($property->propertyManager)
                            <p>🧑‍💼 Managed by {{ $property->propertyManager->first_name }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('properties.index') }}" 
               class="text-indigo-600 hover:underline text-sm">
                &larr; Back to listings
            </a>
        </div>
    </div>
@endsection