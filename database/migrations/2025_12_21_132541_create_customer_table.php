<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('customer_id')->autoIncrement();
            $table->string('customer_name', 128)->nullable();
            $table->string('customer_phone', 32)->nullable();
            $table->string('customer_email', 128)->nullable();
            $table->text('customer_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
