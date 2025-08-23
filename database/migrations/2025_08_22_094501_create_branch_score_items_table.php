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
        Schema::create('branch_score_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_score_id')->constrained('branch_scores')->onDelete('cascade');
            $table->foreignId('score_criteria_id')->constrained('score_criteria')->onDelete('cascade');
            $table->integer('points');
            $table->boolean('is_achieved')->default(false);
            $table->text('review_notes')->nullable();
            $table->datetime('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['branch_score_id', 'score_criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_score_items');
    }
};
