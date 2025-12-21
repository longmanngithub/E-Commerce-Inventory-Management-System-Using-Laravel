<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('order_id')->autoIncrement();
            $table->integer('company_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('order_status', ['Paid', 'Canceled'])->nullable();

            // Foreign Keys
            $table->foreign('company_id', 'fk_order_company')
                ->references('company_id')->on('company');
            $table->foreign('customer_id', 'fk_order_customer')
                ->references('customer_id')->on('customer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
