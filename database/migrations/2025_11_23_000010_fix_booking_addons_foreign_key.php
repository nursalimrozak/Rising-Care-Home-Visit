<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing foreign key constraint
        try {
            Schema::table('booking_addons', function (Blueprint $table) {
                $table->dropForeign(['addon_id']);
            });
        } catch (\Exception $e) {
            // Foreign key might not exist, continue
        }

        // Add new foreign key constraint pointing to addons table
        Schema::table('booking_addons', function (Blueprint $table) {
            $table->foreign('addon_id')
                ->references('id')
                ->on('addons')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Revert back to original constraint
        Schema::table('booking_addons', function (Blueprint $table) {
            $table->dropForeign(['addon_id']);
        });

        Schema::table('booking_addons', function (Blueprint $table) {
            $table->foreign('addon_id')
                ->references('id')
                ->on('addons')
                ->onDelete('cascade');
        });
    }
};
