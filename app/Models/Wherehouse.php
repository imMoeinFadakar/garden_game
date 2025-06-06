<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wherehouse extends Model
{
    protected $table = "warehouses";
    protected $fillable = [
        "user_id",
        "warehouse_level_id",
        "farm_id",
        'amount'
    ];
    protected $appends = ['next_level'];

   /**
    * Get the warehouse_level that owns the Wherehouse
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function warehouse_level()
   {
       return $this->belongsTo(WarehouseLevel::class, 'warehouse_level_id', 'id');
   }


   public function getNextLevelAttribute()
   {
       if (!$this->warehouse_level) return null;
   
       return WarehouseLevel::where('farm_id', $this->farm_id) // هم‌فارمی
           ->where('level_number', '>', $this->warehouse_level->level_number)
           ->orderBy('level_number')
           ->first(['level_number','overcapacity','cost_for_buy','farm_id']);
   }


    public function farm()
    {
        return $this->belongsTo(Farms::class,"farm_id",'id');
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


    public static function findUserWarehouse($userId,$productId)
    {
        return  self::query()
        ->where("user_id",$userId)
        ->where("farm_id",$productId)
        ->first();

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
