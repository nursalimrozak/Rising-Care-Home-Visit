<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'pending_payment' to booking status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('scheduled', 'checked_in', 'in_progress', 'pending_payment', 'completed', 'cancelled', 'failed') DEFAULT 'scheduled'");
    }

    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('scheduled', 'checked_in', 'in_progress', 'completed', 'cancelled', 'failed') DEFAULT 'scheduled'");
    }
};
