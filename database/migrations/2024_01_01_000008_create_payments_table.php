<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY-yyyymmddxxxxxxxxx
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'ewallet']);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('membership_discount', 10, 2)->default(0);
            $table->decimal('voucher_discount', 10, 2)->default(0);
            $table->decimal('points_discount', 10, 2)->default(0);
            $table->integer('points_redeemed')->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
