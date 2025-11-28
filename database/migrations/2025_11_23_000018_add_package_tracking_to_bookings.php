<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_purchase_id')->nullable()->after('service_id')->constrained()->nullOnDelete();
            $table->integer('visit_number')->nullable()->after('package_purchase_id'); // which visit in the package (1-8)
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_purchase_id']);
            $table->dropColumn(['package_purchase_id', 'visit_number']);
        });
    }
};
