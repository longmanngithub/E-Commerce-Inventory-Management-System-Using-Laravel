<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_owner', function (Blueprint $table) {
            $table->integer('owner_id')->autoIncrement();
            $table->string('owner_name', 128)->nullable();
            $table->string('owner_email', 128)->nullable()->unique('owner_email');
            $table->string('owner_password', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->text('owner_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_owner');
    }
};
