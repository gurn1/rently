<?php
namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderUpdate;
use Illuminate\Http\Request;

class WorkOrderUpdateController extends Controller
{
    public function store(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Ensure user belongs to this work order
        $user = auth()->user();
        $canUpdate = $workOrder->raised_by === $user->id
            || $workOrder->assigned_to === $user->id
            || $workOrder->property->property_manager_id === $user->id
            || $user->hasRole('admin');

        if (!$canUpdate) {
            abort(403);
        }

        WorkOrderUpdate::create([
            'work_order_id' => $workOrder->id,
            'user_id'       => $user->id,
            'comment'       => $validated['comment'],
        ]);

        // Redirect back to the correct dashboard
        if ($user->hasRole('property_manager')) {
            return redirect()->route('manager.work-orders.show', $workOrder)
                ->with('success', 'Update added.');
        }

        if ($user->hasRole('tenant')) {
            return redirect()->route('tenant.work-orders.show', $workOrder)
                ->with('success', 'Update added.');
        }

        return redirect()->route('admin.work-orders.show', $workOrder)
            ->with('success', 'Update added.');
    }
}