<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('commissions');
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable for 'service' role
            $table->string('role'); // petugas, admin, superadmin, service
            $table->decimal('amount', 12, 2);
            $table->decimal('percentage', 5, 2); // 60.00, 20.00, etc.
            $table->string('status')->default('pending'); // pending, paid
            $table->foreignId('payout_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
