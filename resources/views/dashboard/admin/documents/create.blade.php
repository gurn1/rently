@extends('layouts.portal')

@section('title', 'Upload Document')

@section('content')
    <div>
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Upload Document</h1>
            <a href="{{ route('admin.documents.index') }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Back to documents
            </a>
        </div>

        <form method="POST"
              action="{{ route('admin.documents.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div class="panel">

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Title</x-input-label>
                    <x-text-input type="text" name="title" value="{{ old('title') }}"/>
                    @error('title') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Tenant</x-input-label>
                    <x-select
                        name="tenant_id"
                        placeholder="Select a tenant"
                        :selected="old('tenant_id')"
                        :options="$tenants->mapWithKeys(fn($t) => [$t->id => $t->first_name . ' ' . $t->last_name])->toArray()"
                    />
                    @error('tenant_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">Document Type</x-input-label>
                    <x-select
                        name="document_type"
                        placeholder="Select a type"
                        :selected="old('document_type')"
                        :options="$documentTypes"
                    />
                    @error('document_type') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">
                        Property <span class="text-gray-400 font-normal">(optional)</span>
                    </x-input-label>
                    <x-select
                        name="property_id"
                        placeholder="No property"
                        :selected="old('property_id')"
                        :options="$properties->pluck('title', 'id')->toArray()"
                    />
                    @error('property_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">
                        Lease <span class="text-gray-400 font-normal">(optional)</span>
                    </x-input-label>
                    <x-select
                        name="lease_id"
                        placeholder="No lease"
                        :selected="old('lease_id')"
                        :options="$leases->mapWithKeys(fn($l) => [$l->id => $l->property->title . ' — ' . $l->tenant->first_name . ' ' . $l->tenant->last_name])->toArray()"
                    />
                    @error('lease_id') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="input-container">
                    <x-input-label class="block text-sm font-medium text-gray-700 mb-1">File</x-input-label>
                    <x-text-input type="file" name="file" accept=".pdf,.doc,.docx"/>
                    <p class="text-xs text-gray-400 mt-1">PDF, DOC or DOCX. Max 10MB.</p>
                    @error('file') <p class="error-field-message">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <x-checkbox-input name="requires_signature" 
                        id="requires_signature" 
                        value="1"
                        :checked="old('requires_signature')"
                    />
                    <x-input-label for="requires_signature" class="text-sm text-gray-700">
                        This document requires a signature from the tenant
                    </x-input-label>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <x-outline-button href="{{ route('admin.documents.index') }}">
                    Cancel
                </x-outline-button>
                <x-primary-button>
                    Upload Document
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection