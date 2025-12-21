<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->integer('log_id')->autoIncrement();
            $table->string('action', 255)->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('entity_affected', 128)->nullable();
            $table->text('details')->nullable();
            $table->string('user_type', 255);
            $table->unsignedBigInteger('user_id');
            $table->string('subject_type', 255)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            // Indexes found in SQL
            $table->index(['user_type', 'user_id'], 'user_log_index');
            $table->index(['subject_type', 'subject_id'], 'subject_log_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
