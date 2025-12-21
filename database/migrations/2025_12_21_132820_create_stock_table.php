<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->integer('stock_id')->autoIncrement();
            $table->integer('product_id')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('stock_purchase_date')->nullable();
            $table->integer('company_id')->nullable();

            // Foreign Keys
            $table->foreign('product_id', 'fk_stock_product')
                ->references('product_id')->on('product');
            $table->foreign('company_id', 'fk_stock_company')
                ->references('company_id')->on('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
