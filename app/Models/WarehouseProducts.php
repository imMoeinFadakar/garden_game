<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProducts extends Model
{
    //

    /**
     * Get the wherehouse that owns the WherehouseProducts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wherehouse()
    {
        return $this->belongsTo(Wherehouse::class,'warehouse_id','id');
    }


    /**
     * Get the product that owns the WherehouseProducts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Products::class,'product_id','id');
    }

}
