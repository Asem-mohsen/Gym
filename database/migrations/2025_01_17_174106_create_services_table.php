<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description');
            $table->integer('duration');
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('requires_payment')->default(false);
            $table->enum('booking_type', ['unbookable', 'free_booking', 'paid_booking'])->default('unbookable');
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
