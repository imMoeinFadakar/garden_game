<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wherehouse extends Model
{
    protected $table = "warehouses";
    protected $fillable = [
        "user_id",
        "warehouse_level_id",
        "warehouse_cap_left",
        "product_id",
        'overcapacity',
        'max_cap_left'
    ];


   /**
    * Get the warehouse_level that owns the Wherehouse
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function warehouse_level()
   {
       return $this->belongsTo(WarehouseLevel::class, 'warehouse_level_id', 'id');
   }

    public function product()
    {
        return $this->belongsTo(Products::class,"product_id",'id');
    }

    /**
     * Get all of the wherehouse_product for the Wherehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wherehouse_product()
    {
        return $this->hasMany(WarehouseProducts::class);
    }


    /**
     * Get the user that owns the Wherehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function addnewWherehouse( $request){
    return $this->create( $request->validated());
    }

    public function updateWherehouse($request){
    $this->update($request->validated());
    return $this;
    }

    public function deleteWherehouse(){
    return $this->delete();
    }

}
