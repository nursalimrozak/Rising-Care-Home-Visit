<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('service_packages')->cascadeOnDelete();
            $table->integer('total_visits'); // 4, 6, or 8 (1 for reguler doesn't need tracking)
            $table->integer('used_visits')->default(0);
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at')->nullable(); // calculated from validity_weeks
            $table->enum('status', ['active', 'completed', 'expired'])->default('active');
            $table->timestamps();
            
            $table->index(['customer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_purchases');
    }
};
