<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Warehouse\UpdatewarehouseRequest;
use App\Http\Requests\V1\User\Warehouse\WarehouseUPdateRequest;
use App\Http\Resources\V1\User\WalletResource;
use App\Http\Resources\V1\User\warehouseResource;
use App\Models\Wallet;
use App\Models\WarehouseLevel;
use App\Models\Wherehouse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class WarehouseController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouse = Wherehouse::query()->where("user_id",1)->first();
        return $this->api(new warehouseResource($warehouse->toArray()),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdatewarehouseRequest $request)
    {
        $userWallet = $this->findUserWallet(); // user wallet
        $userWarehouse = $this->findUserWarehouse(); // user warhouse
        $currentLevel = $this->findCurrentLevel($userWarehouse,$request);  // get current user level 
        $newLevel = $this->findNewLevel($currentLevel->level_number);// get new level 
      
        $price =  $newLevel->cost_for_buy; // new level cost
        $balance = $userWallet->token_amount; // user token balance

        
        // has user enough token??
        $userTokenStatus = $this->hasUserEnoughToken($balance,$price);

        if($userTokenStatus){

            $newBalance = $this->warehouseCost($balance,$price); // minus the price from user wallet
            $payment = $this->newUserBalanceToken($userWallet,$newBalance); // add new user token balance to wallet

            if($payment){

                // dd($newLevel);
                $this->newUserWarehouseLevel($userWarehouse,$newLevel);
                $userWarehouse->load(["product:id,name","warehouse_level:id,level_number"]);
                return $this->api(new warehouseResource($userWarehouse->toArray()),__METHOD__);


            }


            return $this->errorResponse(422,"payment operation failed");



        }

        return $this->errorResponse(422,"you dont have enough token");


    }

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

    /**
     * fidn the warehouse level that user want to achive
     * @param int $levelNumber
     * @return WarehouseLevel|null
     */
    public function findNextLevel($userWarehouse)
    {
        $userWarehouseLevel = $userWarehouse->warehouse_level_id;
        $warehouseLevel = $this->findWarehouseLevel($userWarehouseLevel);
    }

    public function findWarehouseLevel(int $wareHouseLevelId)
    {
        return WarehouseLevel::query()->find($wareHouseLevelId);
    }


    /**
     * find the wallet that user own
     * @return Wallet|null
     */
    public function findUserWallet()
    {
        $userWallet =  Wallet::where("user_id",1)->first();

       if(! $userWallet){
           $userWallet =  Wallet::create(["user_id"=> 1]);
            return  $userWallet;
       }

        return $userWallet;    
    }

    /**
     * find user warehouse
     * @return Wherehouse
     */
    public function findUserWarehouse(): Wherehouse
    {
        $warehouse =  Wherehouse::query()->where("user_id",1)->firstOrNew(); //auth::id
        if(! $warehouse)
            throw new HttpResponseException(response()->json([
                "success" => false,
                "message" => "you dont have a warehouse , call support" 
            ]));


           
        return $warehouse;

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
