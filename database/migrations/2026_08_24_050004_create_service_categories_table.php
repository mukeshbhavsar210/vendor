<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();

            // Parent category for sub-categories
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('service_categories')
                ->nullOnDelete();

            // Category information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Category image/icon
            $table->string('image')->nullable();
            $table->string('icon')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Display
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
