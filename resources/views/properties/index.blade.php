@extends('layouts.public')

@section('title', 'Properties to Rent')

@section('content')
    <div class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Find Your Next Home</h1>
            <p class="text-indigo-200 text-lg">Browse our available properties to rent</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Results count --}}
        <div class="flex justify-between items-center mb-8">
            <p class="text-gray-500 text-sm">{{ $properties->total() }} properties found</p>
        </div>

        {{-- Property grid --}}
        @if($properties->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <p class="text-xl">No properties available at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <a href="{{ route('properties.show', $property) }}" 
                       class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden group">

                        {{-- Image --}}
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($property->images->where('is_featured', true)->first())
                                <img src="{{ $property->images->where('is_featured', true)->first()->path }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    No image available
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition">
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
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
@endsection