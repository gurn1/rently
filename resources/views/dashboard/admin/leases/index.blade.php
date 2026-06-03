@extends('layouts.portal')

@section('title', 'Leases')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">All Leases</h1>
            <p class="text-gray-500 mt-1">{{ $leases->total() }} leases</p>
        </div>
        <a href="{{ route('admin.leases.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + New Lease
        </a>
    </div>

    @if($leases->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No leases found.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Manager</th>
                        <th>Rent</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leases as $lease)
                        <tr>
                            <td class=" text-gray-900">
                                {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                            </td>
                            <td>
                                {{ $lease->property->title }}
                            </td>
                            <td>
                                {{ $lease->property->propertyManager?->first_name }}
                                {{ $lease->property->propertyManager?->last_name }}
                            </td>
                            <td>
                                £{{ number_format($lease->rent_amount, 0) }}/mo
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'Ongoing' }}
                            </td>
                            <td>
                                <span class="text-xs px-2 py-1 rounded capitalize
                                    {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                                       ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-red-100 text-red-700') }}">
                                    {{ $lease->status }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.leases.show', $lease) }}"
                                       class="text-indigo-600 hover:underline">View</a>
                                    <a href="{{ route('admin.leases.edit', $lease) }}"
                                       class="text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST"
                                          action="{{ route('admin.leases.destroy', $lease) }}"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $leases->links() }}
        </div>
    @endif
@endsection