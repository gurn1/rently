@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.portal')

@section('title', $document->title)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.documents.index') }}"
           class="text-sm text-indigo-600 hover:underline">
            &larr; Back to documents
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $document->title }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
            <div class="panel">
                <h2 class="panel-title">Document Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Type</p>
                        <p class="font-medium capitalize">{{ str_replace('_', ' ', $document->document_type) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Uploaded</p>
                        <p class="font-medium">{{ $document->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Uploaded By</p>
                        <p class="font-medium">
                            {{ $document->uploadedBy->first_name }} {{ $document->uploadedBy->last_name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400">Tenant</p>
                        <p class="font-medium">
                            {{ $document->tenant->first_name }} {{ $document->tenant->last_name }}
                        </p>
                    </div>
                    @if($document->property)
                        <div>
                            <p class="text-gray-400">Property</p>
                            <p class="font-medium">{{ $document->property->title }}</p>
                        </div>
                    @endif
                    @if($document->lease)
                        <div>
                            <p class="text-gray-400">Lease Period</p>
                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($document->lease->start_date)->format('d/m/Y') }}
                                — {{ $document->lease->end_date ? \Carbon\Carbon::parse($document->lease->end_date)->format('d/m/Y') : 'Ongoing' }}
                            </p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-400">Requires Signature</p>
                        <p class="font-medium">{{ $document->requires_signature ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($document->requires_signature)
                        <div>
                            <p class="text-gray-400">Signature Status</p>
                            <span class="text-xs px-2 py-1 rounded
                                {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $document->is_signed ? 'Signed ' . $document->signed_at?->format('d/m/Y') : 'Awaiting Signature' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="panel">
                <h2 class="panel-title">File</h2>
                <a href="{{ Storage::url($document->path) }}"
                   target="_blank"
                   class="block w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition text-sm">
                    Download / View
                </a>
            </div>
        </div>
    </div>
@endsection