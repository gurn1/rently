@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
@extends('layouts.public')

@section('title', 'Properties to Rent')

@section('content')
    {{-- Hero --}}
    <div class="bg-slate-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Find Your Next Home</h1>
            <p class="text-slate-400 text-lg mb-8">Browse our available properties to rent</p>

            {{-- Search bar --}}
            <form method="GET" action="{{ route('properties.index') }}"
                  class="max-w-2xl mx-auto flex gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by location or property name..."
                       class="bg-white flex-1 px-4 py-3 rounded text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <x-primary-button>Search</x-primary-button>
    
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Filters sidebar --}}
            <div class="lg:w-64 flex-shrink-0">
                @include('partials/filters')
            </div>

            {{-- Results --}}
            <div class="flex-1">

                {{-- Results header --}}
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-500 text-sm">
                        {{ $properties->total() }} {{ Str::plural('property', $properties->total()) }} found
                        @if(request('search'))
                            for <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
                        @endif
                    </p>

                    {{-- Active filter badges --}}
                    <div class="flex flex-wrap gap-2">
                        @if(request('type'))
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full capitalize">
                                {{ request('type') }}
                            </span>
                        @endif
                        @if(request('bedrooms'))
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">
                                {{ request('bedrooms') }}+ beds
                            </span>
                        @endif
                        @if(request('price_max'))
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">
                                Up to £{{ number_format(request('price_max'), 0) }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($properties->isEmpty())
                    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                        <p class="text-xl mb-2">No properties found</p>
                        <p class="text-sm mb-4">Try adjusting your filters</p>
                        <a href="{{ route('properties.index') }}"
                           class="text-indigo-600 hover:underline text-sm">
                            Clear all filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <a href="{{ route('properties.show', $property) }}"
                               class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden group">

                                {{-- Image --}}
                                <div class="h-48 bg-gray-200 overflow-hidden">
                                    @if($property->images->where('is_featured', true)->first())
                                        <img src="{{ Storage::url($property->images->where('is_featured', true)->first()->path) }}"
                                             alt="{{ $property->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                            No image
                                        </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-2">
                                        <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition leading-tight">
                                            {{ $property->title }}
                                        </h2>
                                        <span class="text-indigo-600 font-bold text-sm whitespace-nowrap ml-2">
                                            £{{ number_format($property->price, 0) }}/mo
                                        </span>
                                    </div>

                                    <p class="text-gray-500 text-sm mb-4">{{ $property->address }}</p>

                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>🛏 {{ $property->bedrooms }} bed</span>
                                        <span>🚿 {{ $property->bathrooms }} bath</span>
                                        @if($property->size)
                                            <span>📐 {{ $property->size }} sq ft</span>
                                        @endif
                                    </div>

                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-xs capitalize bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                            {{ str_replace('_', ' ', $property->property_type) }}
                                        </span>
                                        <span class="text-xs px-2 py-1 rounded capitalize
                                            {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ str_replace('_', ' ', $property->availability_status) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection