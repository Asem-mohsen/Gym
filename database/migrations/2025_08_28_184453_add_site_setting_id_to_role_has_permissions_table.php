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
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('site_setting_id')->nullable()->after('permission_id')->constrained()->onDelete('cascade');
        });

        try {
            Schema::table('role_has_permissions', function (Blueprint $table) {
                $table->dropUnique(['permission_id', 'role_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->unique(['permission_id', 'role_id', 'site_setting_id'], 'role_has_permissions_permission_role_site_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['site_setting_id']);
            $table->dropColumn('site_setting_id');
        });

        // Restore original unique constraint
        try {
            Schema::table('role_has_permissions', function (Blueprint $table) {
                $table->dropUnique('role_has_permissions_permission_role_site_unique');
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->unique(['permission_id', 'role_id']);
        });
    }
};
