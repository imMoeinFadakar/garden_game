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
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger("min_token_value");
            $table->unsignedBigInteger("max_token_value");
            $table->string("farm_image_url");
            $table->unsignedBigInteger("require_token");
            $table->unsignedBigInteger("require_gem");
            $table->unsignedBigInteger("require_referral");
            $table->string("prodcut_image_url");
            $table->string("flage_image_url")->nullable();
            $table->string("description");
            $table->string("background_light_color");
            $table->string("background_bold_color");
            $table->string("header_light_color");
            $table->string("header_bold_color");
            $table->unsignedBigInteger("power");
            $table->unsignedBigInteger("farm_reward")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
