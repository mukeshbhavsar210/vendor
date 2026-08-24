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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Vendor login/account
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Business information
            $table->string('business_name');
            $table->string('slug')->unique();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Business profile
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();

            // Location for nearby service search
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Vendor approval
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'blocked'
            ])->default('pending');

            $table->boolean('is_verified')->default(false);

            // Admin/vendor information
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
