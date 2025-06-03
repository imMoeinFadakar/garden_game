<?php

namespace App\Observers;

use App\Models\Farms;
use Illuminate\Support\Facades\Cache;

class FarmObserver
{
    /**
     * Handle the Farms "created" event.
     */
    public function created(Farms $farms): void
    {
         Cache::forget('index_farm');
    }

    /**
     * Handle the Farms "updated" event.
     */
    public function updated(Farms $farms): void
    {
         Cache::forget('index_farm');
    }

    /**
     * Handle the Farms "deleted" event.
     */
    public function deleted(Farms $farms): void
    {
         Cache::forget('index_farm');
    }


}
