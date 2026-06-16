<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lease;
use App\Models\Property;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $documents = Document::with(['uploadedBy', 'tenant', 'property'])
            ->latest()
            ->paginate(20);

        return view('dashboard.admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = auth()->user()->tenants;
        $properties = Property::where('property_manager_id', auth()->id())->get();
        $leases = Lease::whereHas('property', function ($query) {
            $query->where('properties.property_manager_id', auth()->id());
        })->with(['tenant', 'property'])->get();

        $documentTypes = [
            'tenancy_agreement' => 'Tenancy Agreement',
            'epc'               => 'EPC Certificate',
            'welcome_pack'      => 'Welcome Pack',
            'inspection_report' => 'Inspection Report',
            'notice'            => 'Notice',
            'other'             => 'Other',
        ];

        return view('dashboard.admin.documents.create', compact(
            'tenants', 'properties', 'leases', 'documentTypes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id'          => 'required|exists:users,id',
            'property_id'        => 'nullable|exists:properties,id',
            'lease_id'           => 'nullable|exists:leases,id',
            'title'              => 'required|string|max:255',
            'document_type'      => 'required|string',
            'requires_signature' => 'boolean',
            'file'               => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Store the file
        $path = $request->file('file')->store('documents', 'private');

        Document::create([
            'uploaded_by'        => auth()->id(),
            'tenant_id'          => $validated['tenant_id'],
            'property_id'        => $validated['property_id'] ?? null,
            'lease_id'           => $validated['lease_id'] ?? null,
            'title'              => $validated['title'],
            'document_type'      => $validated['document_type'],
            'requires_signature' => $request->boolean('requires_signature'),
            'path'               => $path,
        ]);

        $tenant = \App\Models\User::find($validated['tenant_id']);
        $tenant->notify(new DocumentUploadedNotification($document));

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load(['uploadedBy', 'tenant', 'property', 'lease']);
        return view('dashboard.admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $document);

        Storage::disk('private')->delete($document->path);
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted.');
    }
}