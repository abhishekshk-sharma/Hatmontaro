<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('action'); // 'payment_initiated', 'payment_verified', 'payment_failed'
            $table->string('payment_id_masked')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
            
            $table->index(['user_id', 'created_at']);
            $table->index(['order_id']);
            $table->index(['action', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_audit_logs');
    }
};