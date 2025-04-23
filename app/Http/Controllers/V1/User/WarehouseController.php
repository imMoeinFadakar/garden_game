<?php

namespace App\Http\Controllers\V1\User;

use App\Models\Farms;
use App\Models\User;
use App\Models\Wallet;
use App\Models\UserFarms;
use App\Models\WarehouseProducts;
use App\Models\Wherehouse;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use App\Models\WarehouseLevel;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\WalletResource;
use App\Http\Resources\V1\User\warehouseResource;
use App\Http\Requests\V1\User\createwarehouseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\V1\User\Warehouse\UpdatewarehouseRequest;
use App\Http\Requests\V1\User\Warehouse\WarehouseUPdateRequest;

class WarehouseController extends BaseUserController
{
  


    public function create(createwarehouseRequest $request,WarehouseProducts $warehouseProducts)
    {
        $user = User::find(auth()->id());
        if($user->warehouse_status === "inactive")
            return $this->api(null,__METHOD__,'you have to active your Warehouse');


        $farmIsValied = $this->isFarmValied($request->farm_id);    

        if(! $farmIsValied)
            return $this->api(null,__METHOD__,'farm is not found');
 



        $warehouseExists = $this->isWarehouseExists($request->farm_id);
        if($warehouseExists)
            return $this->api(null,__METHOD__,'you already have this warehouse');

        $haveUserFarm = $this->hasUserFarm($request->farm_id);

        if(! $haveUserFarm)
            return $this->api(null,__METHOD__,'you have to buy farm first');


        $warehouseLevel = $this->WarehouseLevel($request->farm_id);
        if(! $warehouseLevel)
            return $this->api(null,__METHOD__,'operation failed , call support for warehouse level');




            $newWarehouse = [
                "user_id" => auth()->id(),
                "farm_id" => $request->farm_id,
                "warehouse_level_id" => $warehouseLevel->id
            ];
           $warehouse =   Wherehouse::query()->create($newWarehouse);

            $warehouseProduct = [
                "warehouse_id" => $warehouse->id,
                "farm_id" =>$farmIsValied->id,
                "amount" => $farmIsValied->farm_reward
            ];


            $warehouseProducts->addNewWarehouseProduct($warehouseProduct);



            return $this->api(new warehouseResource($warehouse->toArray()),__METHOD__);
            
    }

    public function isFarmValied($farmId)
    {
        return   Farms::find($farmId) ?: null;
    
    }



    public function hasUserFarm($farmId): bool
    {
        return UserFarms::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id())
        ->exists();
        
    }
    public function WarehouseLevel($farmId)
    {
        return  WarehouseLevel::query()
        ->where("farm_id",$farmId)
        ->where("level_number",1)
        ->first();
    }

    public function isWarehouseExists($farmId): bool
    {
        return Wherehouse::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id()) //auth::id()
        ->exists();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdatewarehouseRequest $request)
    {
         // find auth user
        $validared = $request->Validated();
        $userWarehouse = $this->findUserWarehouse($validared["farm_id"]); // find user warehouse
        if(! $userWarehouse)
            return $this->errorResponse("you dont have warehouse  for this farm");

            // find user wareouse level
        $wrehouseLevel = $this->currentWarehouseLevel($userWarehouse,$validared["farm_id"]); // current level warehouse
        if(! $wrehouseLevel)
            return $this->errorResponse("you wrehouseLevel level is not found,call support");

        $newLevel = $this->findNextLevel($wrehouseLevel,$validared["farm_id"]); // new level warehouse
        if(! $newLevel)
            return $this->api(null,__METHOD__,'you reached to max level in this farm');

        $hasUserenoughToken = $this->hasUserToken($newLevel->cost_for_buy); // check  user have enough token
        if(! $hasUserenoughToken)
            return $this->api(null,__METHOD__,'you dont have enough token');

        $minusUserToken = $this->minusUserToken($newLevel->cost_for_buy);
        if($minusUserToken){

            $userWarehouse->warehouse_level_id = $newLevel->id;
            $userWarehouse->save();

            return $this->api(new warehouseResource($userWarehouse->toArray()),__METHOD__);
        }

    }

    public function hasUserToken(int $price): bool
    {
        $user = auth()->user();
        if($price > $user->token_amount)
            return false;

        return true;    
    }


    public function minusUserToken(int $price): bool
    {
        $user = auth()->user();
        $user->token_amount -= $price;
        return $user->save();

    }




      /**
     * fidn the warehouse level that user want to achive
     * @param int $levelNumber
     * @return WarehouseLevel|false
     */
    public function findNextLevel($currentLevel,$farmId)
    {
        $level = WarehouseLevel::query()
        ->where("level_number",$currentLevel->level_number + 1)
        ->where("farm_id",$farmId)
        ->first();

        if(! $level)
            return false;


        return $level;
    }
  

    public function currentWarehouseLevel($warehouse,$farmId)
    {   
    
      $warehouseLevel =   WarehouseLevel::query()
        ->where("id",$warehouse->warehouse_level_id)
        ->where("farm_id",$farmId)
        ->first();

        if(! $warehouseLevel)
        return false;

        return $warehouseLevel;
    }


    public function findUserWarehouse($farmId)
    {
        
       $userWarehouse =  Wherehouse::query()
        ->where("user_id",auth()->id())
        ->where("farm_id",$farmId)
        ->first();

        if(! $userWarehouse)
            return false;

        return $userWarehouse;

    }









/////////////////////////

    



    public function findCurrentLevel($userWarehouse,$request)
    {
        $userwarehouselevel = WarehouseLevel::query()
        ->where("id",$userWarehouse->warehouse_level_id)
        ->where("product_id",$request->product_id)
        ->first();

        // dd($userwarehouselevel);
        if(! $userwarehouselevel)
            throw new HttpResponseException(response()->json([
                "succes" => false,
                "message" => "you dont have a warehouse level yet, call support"
            ]));

        return $userwarehouselevel;

    }

    public function findNewLevel(int $currentLevelNumber)
    {   
        
        $newLevel = WarehouseLevel::query()
        ->where("level_number" , $currentLevelNumber+1)
        ->first();

        if(! $newLevel)
            throw new HttpResponseException(response()->json([
                "succes" => false,
                "message" => "you reached to max level"
            ]));
      
            
        return $newLevel;    
    }


    /**
     * check that user have enough token to buy new level
     * @param int $userToken
     * @param int $newLevelCost
     * @return bool
     */
    public function hasUserEnoughToken(int $userToken,int $newLevelCost): bool
    {

        if($userToken < $newLevelCost)
            return false;


        return true;
    }

    /**
     *  update user`s warehouse level
     * @param mixed $userWarehouse
     * @param mixed $newLevelId
     */
    public function newUserWarehouseLevel($userWarehouse,$newLevel)
    {   
        // dd($newLevel->overcapacity);
        $userWarehouse->warehouse_level_id = $newLevel->id;
        $userWarehouse->warehouse_cap_left = $newLevel->max_cap_left;
        $userWarehouse->overcapacity = $newLevel->overcapacity;
        return $userWarehouse->save();
    }

    /**
     * add new balance to user wallet
     * @param mixed $userWallet
     * @param mixed $newBalance
     */
    public function newUserBalanceToken($userWallet,$newBalance)
    {
        $userWallet->token_amount = $newBalance;
        return $userWallet->save();
    }

    /**
     * minuse token amount form cost of new level
     * @param mixed $balance
     * @param mixed $price
     * @return float|int
     */
    public function warehouseCost($balance,$price)
    {
        return  $balance - $price;
    }

  

    public function findWarehouseLevel(int $wareHouseLevelId)
    {
        return WarehouseLevel::query()->find($wareHouseLevelId);
    }




  


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
