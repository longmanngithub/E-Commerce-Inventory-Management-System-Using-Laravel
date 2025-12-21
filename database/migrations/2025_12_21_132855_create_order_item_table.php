<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->integer('order_item_id')->autoIncrement();
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('order_item_quantity')->nullable();
            $table->decimal('order_item_unit_price', 10, 2)->nullable();

            // Foreign Keys
            $table->foreign('order_id', 'fk_orderitem_order')
                ->references('order_id')->on('orders');
            $table->foreign('product_id', 'fk_orderitem_product')
                ->references('product_id')->on('product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
