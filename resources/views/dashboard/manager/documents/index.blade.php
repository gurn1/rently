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
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Title</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Tenant</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Type</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Signature</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Uploaded</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($documents as $document)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $document->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $document->tenant->first_name }} {{ $document->tenant->last_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 capitalize">
                                {{ str_replace('_', ' ', $document->document_type) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($document->requires_signature)
                                    <span class="text-xs px-2 py-1 rounded
                                        {{ $document->is_signed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $document->is_signed ? 'Signed' : 'Awaiting' }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Not required</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $document->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
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