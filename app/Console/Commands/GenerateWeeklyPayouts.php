<?php

namespace App\Console\Commands;

use App\Services\CommissionService;
use Illuminate\Console\Command;

class GenerateWeeklyPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payouts:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate weekly payouts for pending commissions';

    /**
     * Execute the console command.
     */
    public function handle(CommissionService $commissionService)
    {
        $this->info('Starting payout generation...');

        try {
            $count = $commissionService->generateWeeklyPayouts();
            $this->info("Successfully generated {$count} payouts.");
        } catch (\Exception $e) {
            $this->error("Error generating payouts: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
