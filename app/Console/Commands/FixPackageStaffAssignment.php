<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PackagePurchase;
use App\Models\Booking;

class FixPackageStaffAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:package-staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix staff assignment for package bookings - assign same staff to all visits in a package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix package staff assignments...');
        
        // Get all package purchases
        $packagePurchases = PackagePurchase::with('bookings')->get();
        
        $totalFixed = 0;
        $totalPackages = 0;
        
        foreach ($packagePurchases as $package) {
            // Find the first booking with assigned staff
            $firstBookingWithStaff = $package->bookings()
                ->whereNotNull('petugas_id')
                ->orderBy('scheduled_date')
                ->first();
            
            if (!$firstBookingWithStaff) {
                // No staff assigned yet in this package, skip
                continue;
            }
            
            $staffId = $firstBookingWithStaff->petugas_id;
            $staffName = $firstBookingWithStaff->petugas->name;
            
            // Update all other bookings in this package that don't have staff
            $bookingsToUpdate = $package->bookings()
                ->whereNull('petugas_id')
                ->get();
            
            if ($bookingsToUpdate->count() > 0) {
                $totalPackages++;
                
                foreach ($bookingsToUpdate as $booking) {
                    $booking->update(['petugas_id' => $staffId]);
                    $totalFixed++;
                    
                    $this->line("  ✓ Booking #{$booking->booking_number} assigned to {$staffName}");
                }
            }
        }
        
        $this->newLine();
        $this->info("✓ Done! Fixed {$totalFixed} bookings across {$totalPackages} packages.");
        
        return Command::SUCCESS;
    }
}
