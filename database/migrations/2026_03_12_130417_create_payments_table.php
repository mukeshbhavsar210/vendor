<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {    
    public function up(){
         Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();

            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();

            $table->string('payment_mode'); // razorpay / cod
            $table->string('status')->default('pending');

            $table->decimal('amount',10,2);
            $table->string('currency')->default('INR');

            $table->longText('payment_data')->nullable();

            $table->timestamps();

            // optional foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('payments');
    }
};