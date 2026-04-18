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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Check if indexes don't exist before adding them
            if (!Schema::hasIndex('activity_logs', 'activity_logs_subject_type_subject_id_index')) {
                $table->index(['subject_type', 'subject_id']);
            }
            if (!Schema::hasIndex('activity_logs', 'activity_logs_action_index')) {
                $table->index('action');
            }
            if (!Schema::hasIndex('activity_logs', 'activity_logs_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['subject_type', 'subject_id']);
            $table->dropIndex('action');
            $table->dropIndex('created_at');
        });
    }
};
