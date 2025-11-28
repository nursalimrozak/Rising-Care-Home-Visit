<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // RS-yyyymmddxxxxxxxxx
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('booking_type', ['on_site', 'home_visit']);
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->text('customer_address')->nullable();
            $table->text('customer_notes')->nullable();
            $table->enum('status', ['scheduled', 'checked_in', 'in_progress', 'completed', 'cancelled', 'failed'])->default('scheduled');
            $table->decimal('estimated_price', 10, 2);
            $table->decimal('final_price', 10, 2)->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price_per_item', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->foreignId('added_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_addons');
        Schema::dropIfExists('bookings');
    }
};
