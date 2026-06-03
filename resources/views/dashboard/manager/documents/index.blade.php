@extends('layouts.portal')

@section('title', 'Documents')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documents</h1>
            <p class="text-gray-500 mt-1">{{ $documents->total() }} documents</p>
        </div>
        <a href="{{ route('manager.documents.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
            + Upload Document
        </a>
    </div>

    @if($documents->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
            <p class="text-xl">No documents yet.</p>
        </div>
    @else
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Tenant</th>
                        <th>Type</th>
                        <th>Signature</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <td class="text-gray-900">
                                {{ $document->title }}
                            </td>
                            <td>
                                {{ $document->tenant->first_name }} {{ $document->tenant->last_name }}
                            </td>
                            <td class="capitalize">
                                {{ str_replace('_', ' ', $document->document_type) }}
                            </td>
                            <td>
                                @if($document->requires_signature)
                                    <span class="text-xs px-2 py-1 rounded
                                        {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $document->is_signed ? 'Signed' : 'Awaiting' }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Not required</span>
                                @endif
                            </td>
                            <td>
                                {{ $document->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('manager.documents.show', $document) }}"
                                       class="text-indigo-600 hover:underline">View</a>
                                    <form method="POST"
                                          action="{{ route('manager.documents.destroy', $document) }}"
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
            {{ $documents->links() }}
        </div>
    @endif
@endsection