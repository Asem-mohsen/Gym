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
            $table->string('paymob_order_id')->nullable()->after('stripe_payment_intent_id');
            $table->text('paymob_payment_key')->nullable()->after('paymob_order_id');
            $table->text('paymob_transaction_id')->nullable()->after('paymob_payment_key');
            $table->string('currency')->default('EGP')->after('amount');
            $table->timestamp('completed_at')->nullable()->after('updated_at');
            $table->timestamp('failed_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'paymob_order_id',
                'paymob_payment_key',
                'paymob_transaction_id',
                'currency',
                'completed_at',
                'failed_at'
            ]);
        });
    }
};
