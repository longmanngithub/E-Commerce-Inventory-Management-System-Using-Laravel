<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreign('company_id')->references('company_id')->on('company');
            $table->string('name');
            $table->string('email');
            $table->string('role'); // Will store 'admin' or 'staff'
            $table->string('token', 64)->unique(); // The unique, secure token
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
