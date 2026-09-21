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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')
                ->constrained('participants')
                ->onDelete('cascade');

            $table->foreignId('round_id')
                ->constrained('rounds')
                ->onDelete('cascade');

            $table->foreignId('round_setting_id')
                ->constrained('round_settings')
                ->onDelete('cascade');

            $table->text('answer');

            $table->unsignedTinyInteger('attempt')
                ->default(1);

            $table->boolean('is_correct');

            $table->integer('score')
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
