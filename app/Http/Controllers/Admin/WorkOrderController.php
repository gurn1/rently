<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workOrders = WorkOrder::with(['property', 'raisedBy', 'assignedTo'])
            ->latest()
            ->paginate(20);

        return view('dashboard.admin.work-orders.index', compact('workOrders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['property', 'raisedBy', 'assignedTo', 'updates.user']);
        return view('dashboard.admin.work-orders.show', compact('workOrder'));
    }
}