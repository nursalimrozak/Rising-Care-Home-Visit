<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_positive'); // auto-calculated (rating >= 4)
            $table->integer('points_awarded')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted']);
            $table->integer('points'); // positive for earned, negative for redeemed
            $table->string('description');
            $table->integer('balance_after');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('reviews');
    }
};
