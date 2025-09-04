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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Add site_setting_id for gym-specific notifications
            $table->unsignedBigInteger('site_setting_id')->nullable();
            $table->foreign('site_setting_id')->references('id')->on('site_settings')->onDelete('cascade');
            
            // Add priority field for high-priority notifications
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Add target_roles for role-specific notifications
            $table->json('target_roles')->nullable();
            
            // Add scheduled_at for scheduled notifications
            $table->timestamp('scheduled_at')->nullable();
            
            // Add expires_at for notification expiration
            $table->timestamp('expires_at')->nullable();
            
            // Indexes for performance
            $table->index(['site_setting_id', 'created_at']);
            $table->index(['priority', 'created_at']);
            $table->index(['scheduled_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
