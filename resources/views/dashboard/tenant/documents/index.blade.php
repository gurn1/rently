@extends('layouts.portal')

@section('title', 'Documents')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Documents</h1>
        <p class="text-gray-500 mt-1">Documents shared with you by your property manager.</p>
    </div>

    @if($documents->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No documents yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($documents as $document)
                <a href="{{ route('tenant.documents.show', $document) }}"
                   class="block bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ $document->title }}</h2>
                            <p class="text-gray-500 text-sm mt-1 capitalize">
                                {{ str_replace('_', ' ', $document->document_type) }}
                            </p>
                            @if($document->property)
                                <p class="text-gray-400 text-xs mt-1">{{ $document->property->title }}</p>
                            @endif
                            <p class="text-gray-400 text-xs mt-1">{{ $document->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @if($document->requires_signature)
                                <span class="text-xs px-2 py-1 rounded
                                    {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $document->is_signed ? 'Signed' : 'Awaiting Signature' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif
@endsection