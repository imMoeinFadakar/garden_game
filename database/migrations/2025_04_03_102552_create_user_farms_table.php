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
        Schema::create('user_farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained("users")
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->unsignedBigInteger("farm_power");
            $table->enum("reward",["paied",'not_paied'])->default('not_paied');

            $table->foreignId('farm_id')
            ->constrained("farms")
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
        Schema::dropIfExists('user_farms');
    }
};
