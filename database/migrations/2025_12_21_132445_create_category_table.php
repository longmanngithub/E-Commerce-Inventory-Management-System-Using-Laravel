<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->integer('category_id')->autoIncrement();
            $table->string('category_name', 128)->nullable();
            $table->text('category_desc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
