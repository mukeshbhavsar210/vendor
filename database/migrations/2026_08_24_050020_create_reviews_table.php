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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
             // Customer
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Service
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Vendor
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            // Rating
            $table->unsignedTinyInteger('rating');

            // Review content
            $table->string('title')->nullable();
            $table->text('comment')->nullable();

            // Admin moderation
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('admin_note')->nullable();

            // Approval information
            $table->timestamp('approved_at')->nullable();

            // Optional vendor response
            $table->text('vendor_reply')->nullable();
            $table->timestamp('vendor_replied_at')->nullable();

            $table->timestamps();

            // Useful indexes
            $table->index(['service_id', 'status']);
            $table->index(['vendor_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['rating', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
