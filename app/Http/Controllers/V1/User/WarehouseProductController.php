<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\AddProduct\AddProdcutRequest;
use App\Http\Resources\V1\User\WarehouseProductResource;
use App\Models\User;
use App\Models\WarehouseLevel;
use App\Models\Wherehouse;
use Illuminate\Http\Request;
use App\Models\WarehouseProducts;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WarehouseProductController extends BaseUserController
{
    public function index()
    {
        $warehouse = WarehouseProducts::query()->where("user_id",1)
        ->with(["warehouse:id,warehouse_cap_left","product:id,name,image_url,min_token_value,max_token_value"])
        ->get();

        return $this->api(WarehouseProductResource::collection($warehouse),__METHOD__);

    }

    public function store(AddProdcutRequest $request, WarehouseProducts $warehouseProducts)
    {


        $userWarehouseStatus = $this->GetUserWarehouseStatus(); // get warehouse_status user

        // check its must be active
        if(! $userWarehouseStatus)
            return $this->api(null,
                __METHOD__,
                "your warehouse is still deactivate",
                false ,
                422);



        $userWarehouse = $this->getUserwarehouse(); // get user warehouse
        $userProducts = $this->getUserProduct(); // get all user`s product

        $allUserProAmount = $this->userProductsAmount($userProducts->toArray()); // get all amount`s of all user product
        $newAmount = $allUserProAmount + $request->amount; // all user amount + request amount

        // do user have enough capacity to save new amount
        $userWarehouseStatus = $this->hasUserEnoughCapacity($userWarehouse->overcapacity,$newAmount);
        $userMaxCapLeftStatus = $this->hasUserEnoughCapacity($userWarehouse->warehouse_cap_left,$request->amount);

        // if no:
        if(! $userWarehouseStatus || ! $userMaxCapLeftStatus)
            return $this->api(null,
                false,
                "dont have enough resource,update your warehouse",
                422 ,
                null);

        // minus the warehouse warehouse_cap_left
        $minusUserCapLeft  = $this->minusUserCap($request->amount);


        if($minusUserCapLeft){

            $validatedRequest = $request->validated();
            $validatedRequest["warehouse_id"] = $userWarehouse->id ;

          $warehouseProducts = $warehouseProducts->addNewWarehouseProduct($validatedRequest);
            return $this->api(new WarehouseProductResource($warehouseProducts->toArray()),__METHOD__);

        }

        return $this->api(null,__METHOD__,"operation failed",false,422);

    }

    /**
     * minus the wallet max_cap_left field
     * @param int $requestAmount
     * @return bool
     */
    public function minusUserCap(int $requestAmount)
    {
        $userWarehouse = $this->getUserwarehouse();
        $userWarehouse->warehouse_cap_left = $userWarehouse->warehouse_cap_left - $requestAmount;
       return  $userWarehouse->save();

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
    public function getUserWarehouse()
    {
        return Wherehouse::query()
            ->where("user_id",1) // add Auth::id() later
            ->first();

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
