<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreignId('discount_percentages_id')
                  ->nullable() 
                  ->after('product_id')
                  ->constrained('discount_percentages') 
                  ->cascadeOnUpdate()
                  ->nullOnDelete(); // set null if related construction deleted        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign(['discount_percentages_id']);
            $table->dropColumn('discount_percentages_id');
        });
    }
};
