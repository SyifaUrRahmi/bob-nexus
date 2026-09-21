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
        Schema::create('round_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')
              ->constrained('rounds')
              ->onDelete('cascade');
              
            $table->unsignedInteger('session_number')->nullable();
            $table->unsignedInteger('question_number')->nullable();
            $table->text('correct_answer');

            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_settings');
    }
};
