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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("telegram_id")->unique();
            $table->string('name');

            $table->string("username")->unique()->nullable();
            $table->enum("gender",["male","female"])->nullable();
            $table->enum("market_status",["active","inactive"])
            ->default("inactive");

            $table->enum('has_parent',['true','false'])->default('false');


            $table->enum("warehouse_status",["active","inactive"])
            ->default("inactive");

            $table->enum("user_status",["active","banned"])
            ->default("active");

            $table->unsignedBigInteger("token_amount")->default(1000);
            $table->unsignedBigInteger("gem_amount")->default(10);
            $table->uuid("referral_code")->unique();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
