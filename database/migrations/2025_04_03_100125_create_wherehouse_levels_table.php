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
        Schema::create('warehouse_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level_number')->unsigned();
            $table->unsignedBigInteger("overcapacity");
            $table->unsignedBigInteger("cost_for_buy");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("max_cap_left");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wherehouse_levels');
    }
};
