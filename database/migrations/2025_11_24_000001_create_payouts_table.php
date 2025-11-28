<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2); // Gross amount (sum of commissions)
            $table->decimal('fee', 12, 2)->default(0); // Admin fee
            $table->decimal('net_amount', 12, 2); // Amount - Fee
            $table->string('status')->default('pending'); // pending, processed
            $table->date('period_start');
            $table->date('period_end');
            $table->string('proof_file')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
