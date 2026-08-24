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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // Vendor
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            // Category
            $table->foreignId('category_id')
                ->constrained('service_categories')
                ->restrictOnDelete();

            // Service information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 12, 2)->nullable();

            $table->enum('price_type', [
                'fixed',
                'hourly',
                'starting_from',
                'quote',
            ])->default('fixed');

            // Location
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();

            // Service status
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'blocked',
            ])->default('pending');

            // Admin controls
            $table->boolean('is_featured')->default(false);
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Search / popularity
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('search_count')->default(0);

            // Cached rating
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Sorting
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // Useful indexes
            $table->index(['vendor_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['city', 'status']);
            $table->index(['status', 'is_featured']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
