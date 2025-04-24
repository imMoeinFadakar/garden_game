<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\AddProduct\AddProdcutRequest;
use App\Http\Resources\V1\User\WarehouseProductResource;
use App\Models\temporaryReward;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\WarehouseLevel;
use App\Models\Wherehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\WarehouseProducts;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Type\TrueType;

class WarehouseProductController extends BaseUserController
{   
    /**
     * get list of user products
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {   

        $warehouse = Wherehouse::query()
        ->where("user_id",auth()->id())
        ->with(['wherehouse_product','warehouse_level:id,level_number,farm_id,overcapacity'])
        ->get();


        return $this->api(WarehouseProductResource::collection($warehouse),__METHOD__);

    }

    public function store(AddProdcutRequest $request)
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
        return $this->errorResponse("you dont have the warehouse, make it first o call support",422);

        # check user has this farm
        $UserFarm = $this->HasUserFarm($request->farm_id);
        if(! $UserFarm)
            return $this->errorResponse("you dont have this farm",422);

        $newPowerAmount = $this->minusUserFarmPower(intval($reward->amount),$UserFarm->farm_power);
        
        if($newPowerAmount < 0){
            
            return $this->errorResponse("you dont have enough space to store this product, repair your farm",422);
            
        }   


        $insertNewValue = $this->insertNewAmount($UserFarm,$newPowerAmount);

        if($insertNewValue){

            $Warehouse = $this->findWarehouseProduct($UserFarm->farm_id,$userWarehouse->id);
            if(! $Warehouse)
                return $this->api(null,__METHOD__,'you dont active your warehouse yet');



            $warehouseLevel = $this->userWarehouseLevel($userWarehouse->warehouse_level_id);
            if(! $warehouseLevel)
                return $this->api(null,__METHOD__,'where house level not exists');




            $newAmount = $Warehouse->amount + $reward->amount;
           $userOvercapacity =  $this->hasUserEnoughWarehouseCap($newAmount,$warehouseLevel->overcapacity);
            if(! $userOvercapacity)
                return $this->api(null,__METHOD__,'your ware house is full');


            $Warehouse->amount += $reward->amount;
            $Warehouse->save();

            $this->deleteTempraryReward($reward);


            return $this->api(new WarehouseProductResource($Warehouse->toArray()),__METHOD__);

        }
            
        return $this->errorResponse("operation failed,call support",422);
 
    }

    public function deleteTempraryReward($reward)
    {
        return $reward->delete();
    }


    public function hasUserEnoughWarehouseCap(int $newProductAmount,int $warehouseMaxCap): bool
    {
        if($newProductAmount > $warehouseMaxCap)
            return true;

         return false;   
    }

    public function userWarehouseLevel(int $userWarehouseLvlId)
    {
        return WarehouseLevel::query()
        ->where("id",$userWarehouseLvlId)
        ->first();
    }


    public function makeNewWarehouse(int $warehouseId,int $farmId,int $amount)
    {
        $request = [
            "warehouse_id" => $warehouseId,
            "farm_id" => $farmId,
            "amount" => $amount
        ];


        return WarehouseProducts::query()
        ->create($request);
    }


    public function findWarehouseProduct(int $farmId,int $warehouseId)
    {
        return  WarehouseProducts::query()
        ->where("farm_id",$farmId)
        ->where("warehouse_id",$warehouseId)
        ->first()?:null;

    }



    public function insertNewAmount($userFarm,$newAmount)
    {
        $userFarm->farm_power = $newAmount;
        return $userFarm->save();
    }


    public function hasUserEnoughPower($amount,$userfarm)
    {
        if($amount > $userfarm->farm_power)
            return  false;

        return true;
        

    }



    public function HasUserFarm($farmId)
    {
        return UserFarms::query()
        ->where("user_id",1)
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
        $user = User::query()->find(1); // add Auth::id() later
        if($user->warehouse_status === "inactive")
            return false;

        return true;
    }

    /**
     * take all product amount 
     * @param array $userProducts
     * @return float|int
     */
    public function userProductsAmount(array $userProducts)
    {
        $totalUsersProductAmount =0;
        foreach($userProducts as $userProduct){
            $totalUsersProductAmount += $userProduct["amount"];
        }
        return $totalUsersProductAmount;
    }

    /**
     * get warehouse products that user own 
     * @return \Illuminate\Database\Eloquent\Collection<int, WarehouseProducts>
     */
    public function getUserProduct()
    {
        return WarehouseProducts::query()
            ->where("warehouse_id",1) // add warehouse_id later later
            ->get();

    }

    /**
     *  find user warehouse
     * @return Wherehouse|null
     */
    public function getUserWarehouse($farmId)
    {
        return Wherehouse::query()
            ->where("user_id",1) // add Auth::id() later
            ->where("farm_id",$farmId)
            ->first()?:null;

    }

    /**
     * check has user enough cacpcity 
     * @param mixed $userWarehouseCap
     * @param mixed $userAmount
     * @return bool
     */
    public function hasUserEnoughCapacity($userWarehouseCap,$userAmount): bool
    {
        if($userWarehouseCap < $userAmount)
            return false;

        return true;
    }


}
