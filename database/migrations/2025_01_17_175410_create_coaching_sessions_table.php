<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coaching_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('cost', 10, 2);
            $table->string('period'); // e.g., "1 hour", "30 minutes"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coaching_sessions');
    }
};
