<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->string('image_url');
            $table->json('images')->nullable(); // Array of additional images
            $table->json('tags')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('style_type'); // casual, formal, ethnic, etc.
            $table->string('occasion'); // wedding, office, party, etc.
            $table->string('color');
            $table->json('sizes')->nullable(); // ['S', 'M', 'L']
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_ai_recommended')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};