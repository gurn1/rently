@extends('layouts.portal')

@section('title', 'My Leases')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Leases</h1>
        <p class="text-gray-500 mt-1">Your current and past tenancy agreements.</p>
    </div>

    @if($leases->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No leases found.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($leases as $lease)
                <a href="{{ route('tenant.leases.show', $lease) }}"
                   class="block bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-gray-900 text-lg">{{ $lease->property->title }}</h2>
                            <p class="text-gray-500 text-sm mt-1">{{ $lease->property->address }}</p>
                            <p class="text-gray-500 text-sm mt-1">
                                {{ \Carbon\Carbon::parse($lease->start_date)->format('d/m/Y') }}
                                —
                                {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('d/m/Y') : 'Ongoing' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-indigo-600 text-lg">£{{ number_format($lease->rent_amount, 0) }}/mo</p>
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $lease->status === 'active' ? 'bg-green-100 text-green-700' :
                                   ($lease->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                   'bg-red-100 text-red-700') }}">
                                {{ $lease->status }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $leases->links() }}
        </div>
    @endif
@endsection