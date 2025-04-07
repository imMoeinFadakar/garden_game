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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('farm_id')
            ->constrained("farms")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->unsignedBigInteger("min_token_value");
            $table->unsignedBigInteger("max_token_value");
            $table->unsignedBigInteger("user_receive_per_hour");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
