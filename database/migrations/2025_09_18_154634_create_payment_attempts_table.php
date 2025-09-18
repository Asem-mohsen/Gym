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
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('gateway')->nullable()->index();               // e.g. 'paymob', 'stripe'
            $table->string('gateway_transaction_id')->nullable();  // Paymob txn id OR Stripe charge id
            $table->string('gateway_integration_id')->nullable(); 
            $table->string('method'); // card|wallet|kiosk
            $table->string('status')->default('pending');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
            
            $table->index(['gateway_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
