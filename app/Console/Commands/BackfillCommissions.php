<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\CommissionService;
use Illuminate\Console\Command;

class BackfillCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:backfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate commissions for existing paid payments that have no commissions';

    /**
     * Execute the console command.
     */
    public function handle(CommissionService $commissionService)
    {
        $this->info('Starting commission backfill...');

        // Find all paid payments that don't have commissions
        $payments = Payment::where('status', 'paid')
            ->whereDoesntHave('commissions')
            ->get();

        $count = $payments->count();

        if ($count === 0) {
            $this->info('No eligible payments found for backfill.');
            return 0;
        }

        $this->info("Found {$count} payments to process.");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($payments as $payment) {
            try {
                $commissionService->calculateAndDistribute($payment);
            } catch (\Exception $e) {
                $this->error("Error processing payment ID {$payment->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Backfill completed successfully.');

        return 0;
    }
}
