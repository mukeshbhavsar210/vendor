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
        Schema::create('vendor_service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('service_categories')
                ->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate vendor/category assignments
            $table->unique(['vendor_id', 'category_id']);

            // Useful indexes
            $table->index('vendor_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_service_categories');
    }
};
