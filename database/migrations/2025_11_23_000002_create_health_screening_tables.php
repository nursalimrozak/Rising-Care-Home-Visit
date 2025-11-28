<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_screening_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('type', ['text', 'boolean', 'scale'])->default('text');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('user_health_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('health_screening_questions')->onDelete('cascade');
            $table->text('response');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_health_responses');
        Schema::dropIfExists('health_screening_questions');
    }
};
