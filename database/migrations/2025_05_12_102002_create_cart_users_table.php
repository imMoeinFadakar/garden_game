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
        Schema::create('cart_users', function (Blueprint $table) {
            $table->id();
            $table->string('cart_number',16)->unique();

            $table->foreignId('user_id')->comment('ایدی کاربر')
            ->constrained('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->date('expire_date')->comment('تاریخ انقضا');

            $table->unsignedInteger('cvv')->comment('cvv2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_users');
    }
};
