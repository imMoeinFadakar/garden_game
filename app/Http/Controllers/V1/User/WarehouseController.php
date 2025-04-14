<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Warehouse\UpdatewarehouseRequest;
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
        $warehouseLevel = $this->findNextLevel($request->level_number); // new level
        if (! $warehouseLevel){
            return $this->errorResponse(400,"level is not found");
        }

        $price =  $warehouseLevel->cost_for_buy; // new level cost
        $balance = $userWallet->token_amount; // user token balance


        // has user enough token??
        $userTokenStatus = $this->hasUserEnoughToken($balance,$price);
        if($userTokenStatus){

            $newBalance = $this->warehouseCost($balance,$price); // minus the price from user wallet
            $payment = $this->newUserBalanceToken($userWallet,$newBalance); // add new user token balance

            if($payment){

                $this->newUserWarehouseLevel($userWarehouse,$warehouseLevel->id);

                return $this->api(new warehouseResource($userWarehouse->toArray()),__METHOD__);


            }


            return $this->errorResponse(422,"payment operation failed");



        }

        return $this->errorResponse(422,"you dont have enough token");


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
    public function newUserWarehouseLevel($userWarehouse,$newLevelId)
    {
        $userWarehouse->warehouse_level_id = $newLevelId;
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
    public function findNextLevel(int $levelNumber)
    {
        return WarehouseLevel::where("level_number",$levelNumber)->first() ?: null;
    }

    /**
     * find the wallet that user own
     * @return Wallet|null
     */
    public function findUserWallet()
    {
        return Wallet::where("user_id",1)->first();
    }

    /**
     * find user warehouse that user own
     * @return Wallet|null
     */
    public function findUserWarehouse()
    {
        return Wherehouse::query()->where("user_id",1)->first();
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
