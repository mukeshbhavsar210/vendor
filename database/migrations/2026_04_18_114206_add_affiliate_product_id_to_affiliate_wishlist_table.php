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
        Schema::table('affiliate_wishlist', function (Blueprint $table) {
             $table->foreignId('affiliate_product_id')
                  ->nullable() // if not always set
                  ->after('user_id')
                  ->constrained('affiliate_wishlist') // references id in constructions table
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
        Schema::table('affiliate_wishlist', function (Blueprint $table) {
            $table->dropForeign(['affiliate_product_id']);
            $table->dropColumn('affiliate_product_id');
        });
    }
};
