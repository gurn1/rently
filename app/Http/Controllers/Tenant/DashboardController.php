<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\WorkOrder;
use App\Models\Document;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    public function index() {
        $activeLease = auth()->user()->leases()->where('status', 'active')->with('property')->first();
        
        $unreadMessages = Conversation::where('tenant_id', auth()->id())
            ->with(['messages' => fn($q) => $q->whereNull('read_at')->where('sender_id', '!=', auth()->id())])
            ->get()
            ->sum(fn($c) => $c->messages->count());
    
        $openWorkOrders = WorkOrder::where('raised_by', auth()->id())
            ->whereIn('status', ['open', 'in_progress', 'pending_review'])
            ->count();
    
        $pendingDocuments = Document::where('tenant_id', auth()->id())
            ->where('requires_signature', true)
            ->where('is_signed', false)
            ->count();

        $failedPayments = Payment::where('tenant_id', auth()->id())
            ->where('status', 'failed')
            ->count();

        $payments = Payment::where('tenant_id', auth()->id())
            ->with('lease.property')
            ->latest()
            ->take(5)
            ->get();

        $documents = Document::where('tenant_id', auth()->id())
            ->latest()
            ->take(4)
            ->get();

        $workOrders = WorkOrder::where('raised_by', auth()->id())
            ->latest()
            ->take(4)
            ->get();

        $propertyManager = $this->propertyManagerDetails();
    
        return view('dashboard.tenant.dashboard', compact('activeLease', 'unreadMessages', 'openWorkOrders', 'pendingDocuments', 'failedPayments', 'payments', 'documents', 'workOrders', 'propertyManager'));
    }

    public function propertyManagerDetails() {
        $propertyManager = auth()->user()->propertyManager->first();

        $contactDetails['profile_image'] = $propertyManager?->profile_image ? Storage::url($propertyManager->profile_image) : null;
        $contactDetails['first_name'] = $propertyManager?->first_name;
        $contactDetails['last_name'] = $propertyManager?->last_name;
        $contactDetails['phone'] = $propertyManager?->phone;
        $contactDetails['email'] = $propertyManager?->email;

        return $contactDetails;
    }
}
