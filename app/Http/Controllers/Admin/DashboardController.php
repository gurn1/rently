<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Models\WorkOrder;

class DashboardController extends Controller
{
    public function index()
    {
        // User stats
        $userStats = [
            'total'    => User::count(),
            'tenants'  => User::role('tenant')->count(),
            'managers' => User::role('property_manager')->count(),
            'admins'   => User::role('admin')->count(),
        ];

        // Property stats
        $propertyStats = [
            'total'       => Property::count(),
            'available'   => Property::where('availability_status', 'available')->count(),
            'occupied'    => Property::where('availability_status', 'occupied')->count(),
            'maintenance' => Property::where('availability_status', 'under_maintenance')->count(),
        ];

        // Lease stats
        $leaseStats = [
            'total'      => Lease::count(),
            'active'     => Lease::where('status', 'active')->count(),
            'pending'    => Lease::where('status', 'pending')->count(),
            'terminated' => Lease::where('status', 'terminated')->count(),
        ];

        // Payment stats
        $paymentStats = [
            'total_collected' => Payment::where('status', 'paid')->sum('amount'),
            'total_pending'   => Payment::where('status', 'pending')->sum('amount'),
            'total_failed'    => Payment::where('status', 'failed')->count(),
            'this_month'      => Payment::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
        ];

        // Work order stats
        $workOrderStats = [
            'open'     => WorkOrder::where('status', 'open')->count(),
            'urgent'   => WorkOrder::where('priority', 'urgent')->whereNotIn('status', ['resolved', 'closed'])->count(),
            'resolved' => WorkOrder::where('status', 'resolved')->count(),
        ];

        // Recent activity
        $recentLeases = Lease::with(['property', 'tenant'])
            ->latest()
            ->take(5)
            ->get();

        $recentWorkOrders = WorkOrder::with(['property', 'raisedBy'])
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->take(5)
            ->get();

        $failedPayments = Payment::with(['tenant', 'lease.property'])
            ->where('status', 'failed')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin.dashboard', compact(
            'userStats',
            'propertyStats',
            'leaseStats',
            'paymentStats',
            'workOrderStats',
            'recentLeases',
            'recentWorkOrders',
            'failedPayments'
        ));
    }
}