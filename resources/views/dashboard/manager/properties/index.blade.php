@extends('layouts.portal')

@section('title', 'My Properties')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Properties</h1>
            <p class="text-gray-500 mt-1">{{ $properties->total() }} properties</p>
        </div>
        <a href="{{ route('manager.properties.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + Add Property
        </a>
    </div>

    @if($properties->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl mb-4">No properties yet.</p>
            <a href="{{ route('manager.properties.create') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition text-sm">
                Add your first property
            </a>
        </div>
    @else
        <div class="">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                        <tr>
                            <td>
                                <a href="{{ route('manager.properties.show', $property) }}"
                                   class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                    {{ $property->title }}
                                </a>
                                <p class="text-gray-400 text-xs mt-1">{{ $property->address }}</p>
                            </td>
                            <td class="capitalize text-gray-600">
                                {{ str_replace('_', ' ', $property->property_type) }}
                            </td>
                            <td class="text-gray-600">
                                £{{ number_format($property->price, 0) }}/mo
                            </td>
                            <td>
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $property->availability_status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ str_replace('_', ' ', $property->availability_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('manager.properties.edit', $property) }}"
                                       class="text-indigo-600 hover:underline">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $properties->links() }}
        </div>
    @endif
@endsection