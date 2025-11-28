<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_purchases', function (Blueprint $table) {
            // Modify enum to include 'cancelled'
            $table->enum('status', ['pending', 'active', 'completed', 'expired', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('package_purchases', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'completed', 'expired'])->default('pending')->change();
        });
    }
};
