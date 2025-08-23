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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('features', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('lockers', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('coaching_sessions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('trainer_information', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('class_schedules', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('class_pricings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('blog_post_shares', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('comment_likes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('score_criteria', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('branch_scores', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('branch_score_items', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('branch_score_histories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('branch_score_review_requests', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('document_site_setting', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove soft deletes from all tables
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('features', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('lockers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('coaching_sessions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('trainer_information', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('class_pricings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('blog_post_shares', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('comment_likes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('score_criteria', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('branch_scores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('branch_score_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('branch_score_histories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('branch_score_review_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('document_site_setting', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
