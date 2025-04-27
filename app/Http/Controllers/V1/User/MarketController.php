<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\MarketResource;
use App\Models\Farms;
use App\Models\MarketHistory;
use App\Models\User;
use App\Models\WarehouseProducts;
use App\Models\Wherehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\MarketRequest;

class MarketController extends BaseUserController
{

    /**
     * get user history
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function userMarketHistory()
    {
       $marketHistory  = MarketHistory::query()
       ->where('user_id',auth()->id())
       ->with(['farm:id,name,prodcut_image_url'])
       ->get(['product_amount','token_amount','created_at','farm_id']);



        return $this->api(MarketResource::collection($marketHistory),__METHOD__);

    }


    /**
     * sell user product
     * @param \App\Http\Requests\V1\User\MarketRequest $request
     * @param \App\Models\MarketHistory $marketHistory
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sellProduct(MarketRequest $request,MarketHistory $marketHistory)
    { 
        $validatedRequest = $request->validated(); // validated request
        $user = auth()->user(); // user
        $farm = $this->findFarm($validatedRequest["farm_id"]); // find farm
        
        if($user->market_status === "inactive") // user market status
        return $this->api(null,__METHOD__,'you have to active your market');
    
        $findwarehouse = $this->findUserWarehouse($validatedRequest["farm_id"]); // find user warehouse
        if(! $findwarehouse)
        return $this->api(null,__METHOD__,'you dont have this warehouse yet');


        // has user enough product
        $hasUserEnoughProduct = $this->hasUserEnoghProduct($findwarehouse->amount,$validatedRequest["amount"]);
        if(! $hasUserEnoughProduct)
        return $this->api(null,__METHOD__,'you dont have enough product');


        // minus user product
        $this->minusUserProduct($findwarehouse,$validatedRequest['amount']);

        // new benefit user from sell
        $newUserBenefit = $this->getUserBenefit($farm->max_token_value,$validatedRequest["amount"]);
        
        // new token amount after sell
       $newTokenAmount =  $this->addNewTokenAmount($user,$newUserBenefit);
        if($newTokenAmount){

            $newRequest = 
            [
                "user_id" => auth()->id(), // user id
                "product_amount" => $validatedRequest["amount"], // new amount
                'token_amount' => $newUserBenefit,
                "farm_id" => $farm->id
            ];

          $marketHistory =   $marketHistory->addNewMarketHistory($newRequest); // add to market history
            $marketHistory->user_id = null;
            return $this->api(new MarketResource($marketHistory->toArray()),__METHOD__);

        }
        


        return $this->api(null,__METHOD__,'operation failed'); // error


    }

    /**
     * new user 
     * @param mixed $user
     * @param mixed $newBenefit
     */
    public function addNewTokenAmount($user,$newBenefit)
    {
        $user->token_amount += $newBenefit;
        return $user->save();
    }

    /**
     * minus User
     * @param mixed $userProduct
     * @param mixed $productAmount
     * @return void
     */
    public function minusUserProduct($userProduct,$productAmount)
    {
        $userProduct->amount -= $productAmount;
        $userProduct->save();
        return;
    }

    /**
     * Summary of getUserBenefit
     * @param int $productValue
     * @param int $productAmount
     * @return int
     */
    public function getUserBenefit(int $productValue,int $productAmount)
    {
        return $productAmount * $productValue;
    }


    /**
     * Summary of findFarm
     * @param int $farmId
     * @return \Illuminate\Database\Eloquent\Builder<Farms>
     */
    public function findFarm(int $farmId)
    {
        return   Farms::find($farmId);
    }

    /**
     * Summary of hasUserEnoghProduct
     * @param int $userProductAmount
     * @param int $requestAmount
     * @return bool
     */
    public function hasUserEnoghProduct(int $userProductAmount,int $requestAmount): bool
    {
        if($userProductAmount < $requestAmount){

            return false;

        }

        return true;    
    }


    /**
     * Summary of findUserWarehouse
     * @param int $userId
     * @param int $farmId
     * @return Wherehouse|null
     */
    public function findUserWarehouse(int $farmId)
    {
       return   Wherehouse::query()
        ->where('user_id',auth()->id())
        ->where('farm_id',$farmId)
        ->first();
    }


    // /**
    //  * Summary of findUserProduct
    //  * @param int $farmId
    //  * @param int $warehouseId
    //  * @return Wherehouse|null
    //  */
    // public function findUserProduct(int $farmId,int $warehouseId)
    // {
    //     return Wherehouse::query()
    //     ->where('farm_id',$farmId)
    //     ->where("user_id",$warehouseId)
    //     ->first();
    // }

}
