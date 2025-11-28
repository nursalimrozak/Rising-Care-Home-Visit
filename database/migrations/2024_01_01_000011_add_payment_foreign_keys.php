<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign keys that reference payments table
        Schema::table('loyalty_transactions', function (Blueprint $table) {
            $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
        });

        Schema::table('voucher_usages', function (Blueprint $table) {
            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('voucher_usages', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });

        Schema::table('loyalty_transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });
    }
};
