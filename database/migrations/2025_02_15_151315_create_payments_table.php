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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('site_setting_id')->nullable()->constrained()->onDelete('set null');
            $table->string('merchant_order_id')->nullable();
            $table->morphs('paymentable');
            $table->string('gateway')->nullable()->default('paymob');
            $table->string('gateway_order_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'failed','cash_pending'])->default('pending');
            $table->string('currency')->default('EGP');
            $table->json('meta')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
