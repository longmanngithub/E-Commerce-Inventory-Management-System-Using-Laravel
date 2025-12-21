<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_order', function (Blueprint $table) {
            $table->integer('subscription_order_id')->autoIncrement();
            $table->enum('subscription_tier', ['Basic', 'Pro', 'Ultimate']);
            $table->decimal('subscription_price', 10, 2);
            $table->boolean('is_paid')->nullable()->default(0);
            $table->integer('monthly');
            $table->dateTime('renew_date')->nullable();
            // In Laravel, useCurrent() handles CURRENT_TIMESTAMP
            $table->dateTime('start_date')->useCurrent();
            $table->dateTime('end_date')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('company_staff_id')->nullable();
            $table->integer('company_admin_id')->nullable();

            // Foreign Keys with Cascade
            $table->foreign('company_id', 'fk_subscription_company')
                ->references('company_id')->on('company')
                ->onDelete('cascade');
            $table->foreign('company_admin_id', 'fk_subscription_company_admin')
                ->references('admin_id')->on('company_admin')
                ->onDelete('cascade');
            $table->foreign('company_staff_id', 'fk_subscription_company_staff')
                ->references('staff_id')->on('company_staff')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_order');
    }
};
