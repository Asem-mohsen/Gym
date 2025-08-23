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
        Schema::create('branch_score_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_score_id')->constrained('branch_scores')->onDelete('cascade');
            $table->foreignId('changed_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('old_score');
            $table->integer('new_score');
            $table->integer('change_amount');
            $table->text('change_reason')->nullable();
            $table->datetime('changed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_score_histories');
    }
};
