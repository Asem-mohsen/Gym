<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('period'); // e.g., "1 month", "3 months"
            $table->json('description');
            $table->decimal('price', 8, 2);
            $table->boolean('status')->default(1); // 1 => active, 0 => inactive
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
