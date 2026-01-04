<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('razorpay_order_id', 50)->unique();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('receipt', 50);
            $table->enum('status', ['created', 'attempted', 'paid', 'failed'])->default('created');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('razorpay_order_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_orders');
    }
};