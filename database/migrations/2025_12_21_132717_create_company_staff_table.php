<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_staff', function (Blueprint $table) {
            $table->integer('staff_id')->autoIncrement();
            $table->string('staff_name', 128)->nullable();
            $table->string('staff_email', 128)->nullable()->unique('staff_email');
            $table->string('staff_password', 255)->nullable();
            $table->json('permissions')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->text('staff_image')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('company_id', 'fk_staff_company')
                ->references('company_id')->on('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_staff');
    }
};
