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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('merchant_order_id')->after('id')->nullable();
            $table->string('gateway')->nullable()->default('paymob');
            $table->string('gateway_order_id')->after('gateway')->nullable();
            $table->json('meta')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'merchant_order_id',
                'gateway',
                'gateway_order_id',
                'meta'
            ]);
        });
    }
};
