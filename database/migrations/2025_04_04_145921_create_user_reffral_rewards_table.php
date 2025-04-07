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
        Schema::create('user_referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("reward_for_generation_one");
            $table->unsignedBigInteger("reward_for_generation_two");
            $table->unsignedBigInteger("reward_for_generation_three");
            $table->unsignedBigInteger("reward_for_generation_four");

            $table->foreignId("farm_id")
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
        Schema::dropIfExists('user_reffral_rewards');
    }
};
