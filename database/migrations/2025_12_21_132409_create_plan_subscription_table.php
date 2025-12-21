<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_subscription', function (Blueprint $table) {
            $table->integer('subscription_id')->autoIncrement();
            $table->enum('subscription_tier', ['Basic', 'Pro', 'Ultimate']);
            $table->decimal('subscription_price', 10, 2);
            $table->integer('product_limit')->nullable();
            $table->integer('monthly');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_subscription');
    }
};
