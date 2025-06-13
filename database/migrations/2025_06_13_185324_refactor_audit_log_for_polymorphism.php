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
        Schema::table('audit_log', function (Blueprint $table) {
            // We explicitly drop the constraint by its real name from your ERD.
            $table->dropForeign('fk_log_staff');

            // Then, we drop the column itself.
            $table->dropColumn('staff_id');

            // Then, we add the new polymorphic columns.
            $table->morphs('user', 'user_log_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            // This handles rolling back the migration if ever needed
            $table->dropMorphs('user', 'user_log_index');
            $table->foreignId('staff_id')->nullable();
        });
    }
};
