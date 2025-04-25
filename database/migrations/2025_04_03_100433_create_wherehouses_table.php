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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained("users")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->foreignId('farm_id')
            ->constrained("farms")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->foreignId("warehouse_level_id")
            ->constrained("warehouse_levels")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->unsignedBigInteger("amount");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wherehouses');
    }
};
