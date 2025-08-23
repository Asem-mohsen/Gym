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
        Schema::create('branch_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->datetime('last_review_date')->nullable();
            $table->datetime('next_review_date')->nullable();
            $table->timestamps();
            
            $table->unique('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_scores');
    }
};
