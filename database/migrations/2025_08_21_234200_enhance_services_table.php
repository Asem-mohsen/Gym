<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->enum('booking_type', ['unbookable', 'free_booking', 'paid_booking'])->default('unbookable')->after('requires_payment');
            $table->boolean('is_available')->default(true)->after('booking_type');
            $table->integer('sort_order')->default(0)->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['booking_type', 'is_available', 'sort_order']);
        });
    }
};
