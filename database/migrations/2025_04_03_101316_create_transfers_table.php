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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("token_amount");

            $table->foreignId('from_user')
            ->constrained("users")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();


            $table->unsignedBigInteger("transaction_number");

            $table->foreignId('to_user')
            ->constrained("users")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
