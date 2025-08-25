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
        Schema::create('gym_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('site_settings')->onDelete('cascade');
            $table->json('report_sections');
            $table->date('date_from');
            $table->date('date_to');
            $table->enum('export_format', ['pdf', 'excel', 'both']);
            $table->string('status')->default('Document Created');
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_reports');
    }
};
