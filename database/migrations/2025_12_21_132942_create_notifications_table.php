<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            // "id" char(36) -> uuid() handles char(36) in MySQL and uuid in Postgres
            $table->uuid('id')->primary();
            $table->string('type', 255);

            // "notifiable_type" varchar(255) & "notifiable_id" bigint UNSIGNED
            // morphs() creates both columns and the index "notifications_notifiable_type_notifiable_id_index"
            $table->morphs('notifiable');

            $table->text('data');
            $table->timestamp('read_at')->nullable();

            // "created_at" & "updated_at" timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
