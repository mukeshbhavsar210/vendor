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
        Schema::create('service_images', function (Blueprint $table) {
            $table->id();

            // Service
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Image
            $table->string('image');

            // Optional image information
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();

            // Gallery ordering
            $table->unsignedInteger('sort_order')->default(0);

            // Primary image
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            // Index
            $table->index(['service_id', 'sort_order']);            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_images');
    }
};
