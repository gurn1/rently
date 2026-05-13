@extends('layouts.portal')

@section('title', 'Upload Document')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Upload Document</h1>
            <a href="{{ route('manager.documents.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to documents
            </a>
        </div>

        <form method="POST"
              action="{{ route('manager.documents.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                    <select name="tenant_id"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a tenant</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->first_name }} {{ $tenant->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tenant_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                    <select name="document_type"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a type</option>
                        @foreach($documentTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('document_type') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('document_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Property
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <select name="property_id"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">No property</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                {{ $property->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Lease
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <select name="lease_id"
                            class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">No lease</option>
                        @foreach($leases as $lease)
                            <option value="{{ $lease->id }}" {{ old('lease_id') == $lease->id ? 'selected' : '' }}>
                                {{ $lease->property->title }} — {{ $lease->tenant->first_name }} {{ $lease->tenant->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lease_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx"
                           class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">PDF, DOC or DOCX. Max 10MB.</p>
                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="requires_signature" id="requires_signature" value="1"
                           {{ old('requires_signature') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="requires_signature" class="text-sm text-gray-700">
                        This document requires a signature from the tenant
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('manager.documents.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Upload Document
                </button>
            </div>
        </form>
    </div>
@endsection