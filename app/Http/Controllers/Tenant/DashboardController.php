<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\WorkOrder;
use App\Models\Document;
use App\Models\Payment;

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

        $documents = Document::where('tenant_id', auth()->id())
            ->latest()
            ->take(4)
            ->get();

        $workOrders = WorkOrder::where('raised_by', auth()->id())
            ->latest()
            ->take(4)
            ->get();
    
        return view('dashboard.tenant.dashboard', compact('activeLease', 'unreadMessages', 'openWorkOrders', 'pendingDocuments', 'failedPayments', 'documents', 'workOrders'));
    }
}
