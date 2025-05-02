<?php

namespace App\Rules\V1\User;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class hasUserEnoughToken implements Rule
{

    protected $table;
    protected $column;
    protected $whereColumn;
    protected $whereValue;

    /**
     * Class constructor.
     */
    public function __construct($table,$column,$whereColumn,$whereValue)
    {
        $this->table = $table;
        $this->column = $column;
        $this->whereColumn = $whereColumn;
        $this->whereValue = $whereValue;
    }


    public function passes($attribute, $value)
    {
        $maxValue = DB::table($this->table)
        ->where($this->whereColumn,$this->whereValue)
        ->value($this->column);


        return $value <= $maxValue;  


    }


    public function message()
    {
        return "you dont have enough token";
    }


}
