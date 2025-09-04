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
        Schema::create('gym_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained('site_settings')->onDelete('cascade');
            
            // Simplified Color Scheme
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('text_color')->nullable();
            
            // Typography & Styling
            $table->string('font_family')->nullable();
            $table->string('border_radius')->nullable();
            $table->string('box_shadow')->nullable();
            
            // Page Sections Configuration (JSON)
            $table->json('home_page_sections')->nullable(); // Which sections to show on home page
            $table->json('section_styles')->nullable(); // Custom styles for each section
            
            // Media Settings (JSON)
            $table->json('media_settings')->nullable(); // Home banner, about banner, etc.
            
            $table->timestamps();
            
            // Ensure one settings record per site setting
            $table->unique('site_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_settings');
    }
};
