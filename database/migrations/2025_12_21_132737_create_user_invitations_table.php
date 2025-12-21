<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('role', 255);
            $table->json('permissions')->nullable();
            $table->string('token', 64)->unique('user_invitations_token_unique');
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('company_id', 'user_invitations_company_id_foreign')
                ->references('company_id')->on('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
