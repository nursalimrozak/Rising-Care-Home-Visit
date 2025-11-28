<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->enum('type', ['text', 'textarea', 'image', 'json'])->default('text');
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('navbar_menus', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->foreignId('parent_id')->nullable()->constrained('navbar_menus')->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle');
            $table->text('description')->nullable();
            $table->string('cta_text');
            $table->string('cta_link');
            $table->string('background_image');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_services_highlight', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_cta_appointment', function (Blueprint $table) {
            $table->id();
            $table->string('section_title');
            $table->text('section_subtitle')->nullable();
            $table->string('background_image')->nullable();
            $table->string('background_color')->nullable();
            $table->string('button_text');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_how_we_work', function (Blueprint $table) {
            $table->id();
            $table->integer('step_number');
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_avatar')->nullable();
            $table->integer('rating'); // 1-5
            $table->text('comment');
            $table->string('service_name')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('section', ['about', 'contact', 'quick_links', 'social_media']);
            $table->string('title');
            $table->text('content'); // JSON for structured data
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
        Schema::dropIfExists('landing_articles');
        Schema::dropIfExists('landing_testimonials');
        Schema::dropIfExists('landing_how_we_work');
        Schema::dropIfExists('landing_cta_appointment');
        Schema::dropIfExists('landing_services_highlight');
        Schema::dropIfExists('hero_slides');
        Schema::dropIfExists('navbar_menus');
        Schema::dropIfExists('site_settings');
    }
};
