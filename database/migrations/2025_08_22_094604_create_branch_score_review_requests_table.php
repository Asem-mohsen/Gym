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
        Schema::create('branch_score_review_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_score_id')->constrained('branch_scores')->onDelete('cascade');
            $table->foreignId('requested_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('requested_at');
            $table->datetime('reviewed_at')->nullable();
            $table->datetime('scheduled_review_date')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_reviewed')->default(false);
            $table->text('request_notes')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_score_review_requests');
    }
};
