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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('user_id')->nullable()->constrained('branches');
            $table->foreignId('schedule_id')->after('branch_id')->nullable()->constrained('class_schedules');
            $table->foreignId('pricing_id')->after('schedule_id')->nullable()->constrained('class_pricings');
            $table->string('status')->after('pricing_id')->default('pending');
            $table->string('amount')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
            $table->dropForeign(['pricing_id']);
            $table->dropColumn('pricing_id');
        });
    }
};
