<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_screening_questions', function (Blueprint $table) {
            $table->text('options')->nullable()->after('type');
        });

        // Modify enum using raw SQL as it's the most reliable way across different DB versions without extra dependencies
        DB::statement("ALTER TABLE health_screening_questions MODIFY COLUMN type ENUM('text', 'boolean', 'scale', 'checklist') NOT NULL DEFAULT 'text'");
    }

    public function down(): void
    {
        Schema::table('health_screening_questions', function (Blueprint $table) {
            $table->dropColumn('options');
        });

        DB::statement("ALTER TABLE health_screening_questions MODIFY COLUMN type ENUM('text', 'boolean', 'scale') NOT NULL DEFAULT 'text'");
    }
};
