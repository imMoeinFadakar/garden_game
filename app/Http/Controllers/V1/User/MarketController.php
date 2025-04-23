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


    public function userMarketHistory(Request $request)
    {
       $marketHistory  = MarketHistory::query()
       ->where('user_id',auth()->id())
       ->get(['product_amount','token_amount']);

        return $this->api(MarketResource::collection($marketHistory),__METHOD__);

    }


    
    public function sellProduct(MarketRequest $request,MarketHistory $marketHistory)
    {
        $validatedRequest = $request->validated();
        $user = auth()->user();
        $farm = $this->findFarm($validatedRequest["farm_id"]);
        
        if($user->market_status === "inactive")
        return $this->api(null,__METHOD__,'you have to active your market');
    
    $findwarehouse = $this->findUserWarehouse(auth()->id(),$validatedRequest["farm_id"]);
    if(! $findwarehouse)
    return $this->api(null,__METHOD__,'you dont have this warehouse yet');



        $userProduct = $this->findUserProduct($validatedRequest["farm_id"],$findwarehouse->id);
        $hasUserEnoughProduct = $this->hasUserEnoghProduct($userProduct->amount,$validatedRequest["amount"]);
        if(! $hasUserEnoughProduct)
        return $this->api(null,__METHOD__,'you dont have enough product');



        $this->minusUserProduct($userProduct,$validatedRequest['amount']);

        $newUserBenefit = $this->getUserBenefit($farm->max_token_value,$validatedRequest["amount"]);
      
       $newTokenAmount =  $this->addNewTokenAmount($user,$newUserBenefit);
        if($newTokenAmount){

            $newRequest = 
            [
                "user_id" => auth()->id(),
                "product_amount" => $validatedRequest["amount"],
                'token_amount' => $newUserBenefit
            ];

          $marketHistory =   $marketHistory->addNewMarketHistory($newRequest);

            return $this->api(new MarketResource($marketHistory->toArray()),__METHOD__);

        }
        


        return $this->api(null,__METHOD__,'operation failed');


    }
    public function addNewTokenAmount($user,$newBenefit)
    {
        $user->token_amount += $newBenefit;
        return $user->save();
    }


    public function minusUserProduct($userProduct,$productAmount)
    {
        $userProduct->amount -= $productAmount;
        $userProduct->save();
        return;
    }

    public function getUserBenefit(int $productValue,int $productAmount)
    {
        return $productAmount * $productValue;
    }



    public function findFarm(int $farmId)
    {
        return   Farms::find($farmId);
    }

    public function hasUserEnoghProduct(int $userProductAmount,int $requestAmount): bool
    {
        if($userProductAmount < $requestAmount){

            return false;

        }

        return true;    
    }



    public function findUserWarehouse(int $userId,int $farmId)
    {
       return   Wherehouse::query()
        ->where('user_id',$userId)
        ->where('farm_id',$farmId)
        ->first();
    }



    public function findUserProduct(int $farmId,int $warehouseId)
    {
        return WarehouseProducts::query()
        ->where('farm_id',$farmId)
        ->where("warehouse_id",$warehouseId)
        ->first();
    }

}
