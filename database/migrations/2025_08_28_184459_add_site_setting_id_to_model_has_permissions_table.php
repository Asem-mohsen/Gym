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
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('site_setting_id')->nullable()->after('permission_id')->constrained()->onDelete('cascade');
        });

        // Drop and recreate unique constraint more safely
        try {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                $table->dropUnique(['permission_id', 'model_type', 'model_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unique(['permission_id', 'model_type', 'model_id', 'site_setting_id'], 'model_has_permissions_permission_model_site_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['site_setting_id']);
            $table->dropColumn('site_setting_id');
        });

        // Restore original unique constraint
        try {
            Schema::table('model_has_permissions', function (Blueprint $table) {
                $table->dropUnique('model_has_permissions_permission_model_site_unique');
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unique(['permission_id', 'model_type', 'model_id']);
        });
    }
};
