<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove site_setting_id from roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['site_setting_id']);
            $table->dropColumn('site_setting_id');
        });

        // Remove role_id from users table as we'll use many-to-many relationship
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }

    public function down(): void
    {
        // Add back site_setting_id to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('site_setting_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Add back role_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('is_admin')->constrained('roles')->onDelete('cascade');
        });
    }
};
