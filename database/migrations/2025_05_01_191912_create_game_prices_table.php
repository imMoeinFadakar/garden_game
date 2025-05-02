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
        Schema::create('game_prices', function (Blueprint $table) {
            $table->id()->comment("id");
            $table->string('unite')->comment('واحد');
            $table->string("unite_price")->comment("قینت واحد");
            $table->string("convert_to")->comment('تبدیل می شود به');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_prices');
    }
};
