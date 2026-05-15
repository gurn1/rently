<?php
namespace App\Console\Commands;

use App\Models\Lease;
use App\Services\LeasePaymentService;
use Illuminate\Console\Command;

class ExtendLeasePayments extends Command
{
    protected $signature   = 'payments:extend-leases';
    protected $description = 'Extend payment records for open-ended leases';

    public function handle(LeasePaymentService $service): void
    {
        // Find active leases with no end date
        $leases = Lease::where('status', 'active')
            ->whereNull('end_date')
            ->get();

        foreach ($leases as $lease) {
            $service->extendPayments($lease, 3);
            $this->info('Extended payments for lease ' . $lease->id);
        }

        $this->info('Lease payments extended: ' . $leases->count());
    }
}