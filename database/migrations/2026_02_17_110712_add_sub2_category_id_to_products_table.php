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
            $table->foreignId('sub_sub_category_id')
                ->after('sub_category_id')
                ->nullable() 
                ->constrained('sub_sub_categories')
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
            $table->dropForeign(['sub_sub_category_id']);
            $table->dropColumn('sub_sub_category_id');  
        });
    }
};
