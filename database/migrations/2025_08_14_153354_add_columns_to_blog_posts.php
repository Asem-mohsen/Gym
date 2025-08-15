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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('quote_author_name')->after('excerpt')->nullable();
            $table->string('quote_author_title')->after('quote_author_name')->nullable();
            $table->text('author_comment')->after('quote_author_title')->nullable();
            $table->text('description')->after('author_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('quote_author_name');
            $table->dropColumn('quote_author_title');
            $table->dropColumn('author_comment');
            $table->dropColumn('description');
        });
    }
};
