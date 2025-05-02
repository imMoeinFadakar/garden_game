<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePrice extends Model
{
    protected $table = "game_prices";

    protected $fillable = [
        "unite",
        "unite_price",
        "convert_to"
    ];



}
