<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Notifications\DocumentSignedNotification;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('tenant_id', auth()->id())
            ->with(['property', 'lease'])
            ->latest()
            ->paginate(20);

        return view('dashboard.tenant.documents.index', compact('documents'));
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        $document->load(['property', 'lease']);

        return view('dashboard.tenant.documents.show', compact('document'));
    }

    public function sign(Document $document)
    {
        $this->authorize('view', $document);

        if (!$document->requires_signature || $document->is_signed) {
            return redirect()->route('tenant.documents.show', $document)
                ->with('error', 'This document cannot be signed.');
        }

        $document->update([
            'is_signed' => true,
            'signed_at' => now(),
        ]);

        $uploader = \App\Models\User::find($document->uploaded_by);
        $uploader->notify(new DocumentSignedNotification($document));

        return redirect()->route('tenant.documents.show', $document)
            ->with('success', 'Document signed successfully.');
    }
}