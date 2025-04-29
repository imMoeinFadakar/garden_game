<?php

namespace App\Http\Controllers\V1\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Farms;
use App\Models\UserFarms;
use App\Models\Wherehouse;
use App\Models\WarehouseLevel;
use App\Models\temporaryReward;
use App\Http\Resources\V1\User\warehouseResource;
use App\Http\Requests\V1\User\createwarehouseRequest;
use App\Http\Requests\V1\User\AddProduct\AddProdcutRequest;
use App\Http\Requests\V1\User\Warehouse\UpdatewarehouseRequest;

class WarehouseController extends BaseUserController
{
  
      /**
     * get list of user products
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {   

        $warehouse = Wherehouse::query()
        ->where("user_id",auth()->id())
        ->with(['warehouse_level:id,level_number,farm_id,overcapacity','farm:id,name,prodcut_image_url,header_light_color'])
        ->get(['id','farm_id','warehouse_level_id','amount']);


        return $this->api(warehouseResource::collection($warehouse),__METHOD__);

    }

    /**
     * create new warehouse
     * @param \App\Http\Requests\V1\User\createwarehouseRequest $request
     * @param \App\Models\Wherehouse $warehouseProducts
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(createwarehouseRequest $request,Wherehouse $Wherehouse)
    {
        $user = auth()->user(); // find  user`
        if($user->warehouse_status === "inactive") // is user warehouse active?
            return $this->api(null,__METHOD__,'you have to active your Warehouse');


        $findFarm = $this->isFarmValied($request->farm_id);  // find farm  

        if(! $findFarm) // if farm dosent exists
            return $this->api(null,__METHOD__,'farm is not found');
 

        $warehouseExists = $this->isWarehouseExists($request->farm_id); // is warehouse exists
        if($warehouseExists)
            return $this->api(null,__METHOD__,'you already have this warehouse');

        $haveUserFarm = $this->hasUserFarm($request->farm_id); // has user this farm

        if(! $haveUserFarm) // if user hasnt farm
            return $this->api(null,__METHOD__,'you have to buy farm first');


        $warehouseLevel = $this->WarehouseLevel($request->farm_id); // find warehouse
        if(! $warehouseLevel)
            return $this->api(null,__METHOD__,'operation failed , call support for warehouse level');

            //craete new warhouse
            $newWarehouse = [
                "user_id" => auth()->id(),
                "farm_id" => $request->farm_id,
                "warehouse_level_id" => $warehouseLevel->id,
                "amount" => $findFarm->farm_reward
            ];
           $warehouse =   Wherehouse::query()->create($newWarehouse);

    
            $warehouse->user_id = null;
            return $this->api(new warehouseResource($warehouse->toArray()),__METHOD__);
            
    }

    /**
     * check farm exists
     * @param int $farmId
     * @return \Illuminate\Database\Eloquent\Collection<int, Farms>|null
     */
    public function isFarmValied(int $farmId)
    {
        return   Farms::find($farmId) ?: null;
    
    }


    /**
     * is user farm owner
     * @param int  $farmId
     * @return bool
     */
    public function hasUserFarm(int $farmId): bool
    {
        return UserFarms::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id())
        ->exists();
        
    }
    /**
     * find first warehouse level
     * @param int $farmId
     * @return WarehouseLevel|null
     */
    public function WarehouseLevel(int $farmId)
    {
        return  WarehouseLevel::query()
        ->where("farm_id",$farmId)
        ->where("level_number",1)
        ->first();
    }


    /**
     * is warehouse exists before
     * @param mixed $farmId
     * @return bool
     */
    public function isWarehouseExists($farmId): bool
    {
        return Wherehouse::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id()) //auth::id()
        ->exists();
    }

    /**
     *  update usee warehouse 
     * @param \App\Http\Requests\V1\User\Warehouse\UpdatewarehouseRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
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

        // find warehouse next level
        $newLevel = $this->findNextLevel($wrehouseLevel,$validared["farm_id"]); // new level warehouse
        if(! $newLevel)
            return $this->api(null,__METHOD__,'you reached to max level in this farm');

        // check user have enough token
        $hasUserenoughToken = $this->hasUserToken($newLevel->cost_for_buy); // check  user have enough token
        if(! $hasUserenoughToken)
            return $this->api(null,__METHOD__,'you dont have enough token');

            // minus user token
        $minusUserToken = $this->minusUserToken($newLevel->cost_for_buy);
        if($minusUserToken){

            $userWarehouse->warehouse_level_id = $newLevel->id;
            $userWarehouse->save();

            $userWarehouse->load(['warehouse_level:id,level_number,overcapacity']); // get new level option
            $userWarehouse->user_id = null;
            return $this->api(new warehouseResource($userWarehouse->toArray()),__METHOD__);
        }

        return $this->api(null,__METHOD__,'operation failed');

    }

    /**
     * has user enough token
     * @param int $price
     * @return bool
     */
    public function hasUserToken(int $price): bool
    {
        $user = auth()->user();
        if($price > $user->token_amount)
            return false;

        return true;    
    }

    /**
     * Summary of minusUserToken
     * @param int $price
     * @return bool
     */
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
  
    /**
     * Summary of currentWarehouseLevel
     * @param \App\Models\Wherehouse $warehouse
     * @param int $farmId
     * @return bool|WarehouseLevel
     */
    public function currentWarehouseLevel(Wherehouse $warehouse,int $farmId)
    {   
    
      $warehouseLevel =   WarehouseLevel::query()
        ->where("id",$warehouse->warehouse_level_id)
        ->where("farm_id",$farmId)
        ->first();

        if(! $warehouseLevel)
        return false;

        return $warehouseLevel;
    }

    /**
     * Summary of findUserWarehouse
     * @param int  $farmId
     * @return bool|Wherehouse
     */
    public function findUserWarehouse(int $farmId)
    {
        
       $userWarehouse =  Wherehouse::query()
        ->where("user_id",auth()->id())
        ->where("farm_id",$farmId)
        ->first();

        if(! $userWarehouse)
            return false;

        return $userWarehouse;

    }


    public function storeProduct(AddProdcutRequest $request)
    {


        $userWarehouseStatus = $this->GetUserWarehouseStatus(); // get warehouse_status user

        $validated = $request->validated();

        $reward = temporaryReward::find($validated['reward_id']);

        if(Carbon::now()->greaterThan($reward->ex_time))
            return $this->api(null,__METHOD__,'reward has been expired');



        if( $reward->user_id != auth()->id())
            return $this->api(null,__METHOD__,'this reward isnt yours');

     

        // check its must be active
        if(! $userWarehouseStatus)
            return $this->api(null,
                __METHOD__,
                "your warehouse is still inactive",
                false ,
                422);


        # check user has warehouse for this farm
        $userWarehouse = $this->getUserWarehouse($request->farm_id);
        if(! $userWarehouse)
        return $this->errorResponse("you dont have the warehouse, make it first or call support",422);

        # check user has this farm
        $UserFarm = $this->UserFarm($request->farm_id);
        if(! $UserFarm)
            return $this->errorResponse("you dont have this farm",422);

        $newPowerAmount = $this->minusUserFarmPower(intval($reward->amount),$UserFarm->farm_power);
        
        if($newPowerAmount < 0){
            
            return $this->errorResponse("you dont have enough space to store this product, repair your farm",422);
            
        }   


        $insertNewValue = $this->insertNewAmount($UserFarm,$newPowerAmount);

        if($insertNewValue){

            $Warehouse = $this->findUserWarehouse($UserFarm->farm_id);
            if(! $Warehouse)
                return $this->api(null,__METHOD__,'you dont active your warehouse yet');



            $warehouseLevel = $this->userWarehouseLevel($userWarehouse->warehouse_level_id);
            if(! $warehouseLevel)
                return $this->api(null,__METHOD__,'where house level not exists');




            $newAmount = $Warehouse->amount + $reward->amount;
           $userOvercapacity =  $this->hasUserEnoughWarehouseCap($newAmount,$warehouseLevel->overcapacity);
     
          
     
           if(! $userOvercapacity)
                return $this->api(null,__METHOD__,'your warehouse is full');


            $Warehouse->amount += $reward->amount;
            $Warehouse->save();

            $this->deleteTempraryReward($reward);


            return $this->api(new warehouseResource($Warehouse->toArray()),__METHOD__);

        }
            
        return $this->errorResponse("operation failed,call support",422);
 
    }
    /**
     * Summary of deleteTempraryReward
     * @param  $reward
     * @return bool|null
     */
    public function deleteTempraryReward($reward)
    {
        return $reward->delete();
    }

    /**
     * chak user cap 
     * @param int $newProductAmount
     * @param int $warehouseMaxCap
     * @return bool
     */
    public function hasUserEnoughWarehouseCap(int $newProductAmount,int $warehouseMaxCap): bool
    {
        if($newProductAmount > $warehouseMaxCap)
            return false;

         return true;   
    }

    /**
     * find warehouse level
     * @param int $userWarehouseLvlId
     * @return WarehouseLevel|null
     */
    public function userWarehouseLevel(int $userWarehouseLvlId)
    {
        return WarehouseLevel::query()
        ->where("id",$userWarehouseLvlId)
        ->first();
    }

    /**
     * insert new user to database
     * @param mixed $userFarm
     * @param mixed $newAmount
     */
    public function insertNewAmount($userFarm,$newAmount)
    {
        $userFarm->farm_power = $newAmount;
        return $userFarm->save();
    }




    /**
     * find user farm
     * @param mixed $farmId
     * @return UserFarms|null
     */
    public function UserFarm($farmId)
    {
        return UserFarms::query()
        ->where("user_id",auth()->id())
        ->where("farm_id",$farmId)
        ->first() ?:null;
    }




    /**
     * minus the wallet max_cap_left field
     * @param int $requestAmount
     * @return bool
     */
    public function minusUserFarmPower(int $requestAmount,int $userFarmPower)
    {
        return  $userFarmPower - $requestAmount; 

    }

    /**
     * check user warehouse status is active or inactive
     * @return bool
     */
    public function GetUserWarehouseStatus()
    {
        $user = auth()->user(); // add Auth::id() later
        if($user->warehouse_status === "inactive")
            return false;

        return true;
    }


  

    /**
     *  find user warehouse
     * @return Wherehouse|null
     */
    public function getUserWarehouse($farmId)
    {
        return Wherehouse::query()
            ->where("user_id",auth()->id()) // add Auth::id() later
            ->where("farm_id",$farmId)
            ->first()?:null;

    }

  
}
