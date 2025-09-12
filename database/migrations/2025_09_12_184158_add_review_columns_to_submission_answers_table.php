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
        Schema::table('submission_answers', function (Blueprint $table) {
            $table->integer('score')->nullable()->after('metadata');
            $table->enum('rating', ['excellent', 'good', 'average', 'poor'])->nullable()->after('score');
            $table->text('comments')->nullable()->after('rating');
            $table->timestamp('reviewed_at')->nullable()->after('comments');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_answers', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['score', 'rating', 'comments', 'reviewed_at', 'reviewed_by']);
        });
    }
};
