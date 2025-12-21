<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company', function (Blueprint $table) {
            $table->integer('company_id')->autoIncrement();
            $table->string('company_name', 128)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('company_address', 255)->nullable();
            $table->string('company_email', 128)->nullable();
            $table->text('company_website')->nullable();
            $table->string('company_telephone', 32)->nullable();
            $table->text('company_image')->nullable();
            $table->text('company_desc')->nullable();
            $table->timestamp('register_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
