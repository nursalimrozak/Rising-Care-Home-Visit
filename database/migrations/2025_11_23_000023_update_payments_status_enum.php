<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Modify enum to include 'pending_verification' and 'cancelled'
            $table->enum('status', ['pending', 'pending_verification', 'paid', 'failed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to original enum (assuming it was pending, paid, failed)
            // Note: This might fail if there are records with new statuses.
            // Ideally we should handle data migration but for now we just revert schema.
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending')->change();
        });
    }
};
