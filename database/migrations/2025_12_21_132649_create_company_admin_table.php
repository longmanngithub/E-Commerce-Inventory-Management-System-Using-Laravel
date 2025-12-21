<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_admin', function (Blueprint $table) {
            $table->integer('admin_id')->autoIncrement();
            $table->string('admin_name', 128)->nullable();
            $table->string('admin_email', 128)->nullable()->unique('admin_email');
            $table->string('admin_password', 255)->nullable();
            $table->boolean('is_owner')->default(0);
            $table->string('remember_token', 100)->nullable();
            $table->text('admin_image')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('company_id', 'fk_admin_company')
                ->references('company_id')->on('company');
            $table->foreign('subscription_id', 'fk_admin_subscription')
                ->references('subscription_id')->on('plan_subscription');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_admin');
    }
};
