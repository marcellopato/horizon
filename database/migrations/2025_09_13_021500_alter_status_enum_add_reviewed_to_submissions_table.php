<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Add 'reviewed' to the ENUM of submissions.status
        DB::statement(
            "ALTER TABLE `submissions` MODIFY `status` " .
            "ENUM('in_progress','completed','submitted','reviewed') " .
            "NOT NULL DEFAULT 'in_progress'"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Convert any 'reviewed' back to 'completed' before shrinking the enum
        DB::table('submissions')
            ->where('status', 'reviewed')
            ->update(['status' => 'completed']);

        DB::statement(
            "ALTER TABLE `submissions` MODIFY `status` " .
            "ENUM('in_progress','completed','submitted') " .
            "NOT NULL DEFAULT 'in_progress'"
        );
    }
};
