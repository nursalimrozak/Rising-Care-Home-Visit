<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create categories table
        Schema::create('health_question_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add category_id to health_screening_questions
        Schema::table('health_screening_questions', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('health_question_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('health_screening_questions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('health_question_categories');
    }
};
