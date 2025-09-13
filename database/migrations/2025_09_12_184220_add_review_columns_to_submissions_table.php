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
        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('overall_score')->nullable()->after('metadata');
            $table->enum('recommendation', [
                'strongly_recommend',
                'recommend',
                'neutral',
                'not_recommend',
                'strongly_not_recommend',
            ])->nullable()->after('overall_score');
            $table->text('overall_comments')->nullable()->after('recommendation');
            $table->timestamp('reviewed_at')->nullable()->after('overall_comments');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->after('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'overall_score',
                'recommendation',
                'overall_comments',
                'reviewed_at',
                'reviewed_by',
            ]);
        });
    }
};
