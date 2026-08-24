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
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            // Customer - nullable for guest searches
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Search keyword
            $table->string('keyword');
            $table->string('normalized_keyword');

            // Optional category
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('service_categories')
                ->nullOnDelete();

            // Location
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();

            // Search statistics
            $table->unsignedBigInteger('search_count')->default(1);

            $table->timestamp('last_searched_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('normalized_keyword');
            $table->index(['category_id', 'search_count']);
            $table->index(['city', 'search_count']);
            $table->index('last_searched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searches');
    }
};
