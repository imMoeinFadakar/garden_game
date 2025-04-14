<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseProducts extends Model
{
    protected $fillable = [
        "warehouse_id"
        ,"product_id"
        ,"amount"
    ];

    /**
     * Get the wherehouse that owns the WherehouseProducts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
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

    public function addNewWarehouseProduct($request)
    {
        return $this->query()->create($request);
    }

    public function updateWarehouseProduct($request)
    {
        $this->update($request);
        return $this;
    }
}
