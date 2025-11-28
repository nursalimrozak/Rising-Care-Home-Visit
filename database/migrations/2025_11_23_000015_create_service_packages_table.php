<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Reguler, Eksekutif, VIP, Premium
            $table->integer('visit_count'); // 1, 4, 6, 8
            $table->integer('validity_weeks'); // 0, 3, 4, 6 (0 for single visit)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
