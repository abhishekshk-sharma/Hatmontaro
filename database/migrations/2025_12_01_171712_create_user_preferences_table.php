<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('style_preferences')->nullable(); // ['minimalist', 'bohemian', 'classic']
            $table->json('color_preferences')->nullable(); // ['black', 'white', 'blue']
            $table->json('size_preferences')->nullable(); // ['M', 'L']
            $table->json('occasion_preferences')->nullable(); // ['office', 'casual', 'formal']
            $table->json('budget_range')->nullable(); // ['min' => 0, 'max' => 500]
            $table->text('dislikes')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};