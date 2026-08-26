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
        Schema::table('products', function (Blueprint $table) {
             $table->foreignId('sub2_category_id')
                  ->nullable() 
                  ->constrained('products') 
                  ->cascadeOnUpdate()
                  ->nullOnDelete();     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {             
            $table->dropForeign(['sub2_category_id']);
            $table->dropColumn('sub2_category_id');        
        });
    }
};
