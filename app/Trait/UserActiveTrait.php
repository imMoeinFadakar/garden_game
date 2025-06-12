<?php

namespace App\Trait;

trait UserActiveTrait
{
    public function isUserActive(): bool
    {
        if(auth()->user()->user_status === "banned")
            return false;


        return true;
    }
}
