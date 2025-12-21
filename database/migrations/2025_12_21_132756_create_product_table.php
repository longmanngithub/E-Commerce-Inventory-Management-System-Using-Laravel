<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('product_id')->autoIncrement();
            $table->string('product_name', 128)->nullable();
            $table->string('status', 100)->default('Active');
            $table->string('product_SKU', 128)->nullable();
            $table->date('product_expiry_date')->nullable();
            $table->decimal('product_price', 10, 2)->nullable();
            $table->integer('reorder_point')->default(10);
            $table->text('product_desc')->nullable();
            $table->text('product_image')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('category_id', 'product_category')
                ->references('category_id')->on('category');
            $table->foreign('company_id', 'product_company')
                ->references('company_id')->on('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
