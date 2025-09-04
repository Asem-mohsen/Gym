<?php

use App\Models\Feature;
use App\Models\SiteSetting;
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
        Schema::table('features', function (Blueprint $table) {
            $table->foreignId('site_setting_id')->after('id')->nullable()->constrained('site_settings')->onDelete('cascade');
            $table->index('site_setting_id');
        });

        $firstSiteSetting = SiteSetting::first();
        if ($firstSiteSetting) {
            Feature::whereNull('site_setting_id')->update(['site_setting_id' => $firstSiteSetting->id]);
        }

        Schema::table('features', function (Blueprint $table) {
            $table->foreignId('site_setting_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign(['site_setting_id']);
            $table->dropIndex(['site_setting_id']);
            $table->dropColumn('site_setting_id');
        });
    }
};
