<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_checkin_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained()->onDelete('cascade');
            $table->enum('preferred_checkin_method', ['self_scan', 'gate_scan', 'both']);
            $table->boolean('enable_self_scan')->default(true);
            $table->boolean('enable_gate_scan')->default(true);
            $table->boolean('require_branch_selection')->default(false);
            $table->boolean('allow_multiple_checkins_per_day')->default(false)->comment('Allow users to check in multiple times per day (e.g., leave and return)');
            $table->integer('checkin_cooldown_minutes')->default(5)->comment('Minimum minutes between check-ins to prevent spam (e.g., 5 minutes)');
            $table->json('enabled_branches')->nullable(); // Array of branch IDs where check-in is enabled
            $table->timestamps();
            
            // Ensure one setting per gym
            $table->unique('site_setting_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_checkin_settings');
    }
};
