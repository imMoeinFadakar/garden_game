<?php

namespace App\Http\Controllers\V1\User;

use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\UserReferralReward;
use App\Models\Wherehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\PayRewardRequest;

class PayRequestControler extends Controller
{
    public function newPayingRequest(PayRewardRequest $request) 
    {
        $user = User::find(auth()->id());
        if($user->warehouse_status === "inactive")
            return $this->api(null,__METHOD__,'you have to active your Warehouse');


        $validated = $request->validated();

        $userWarehouse = $this->isWarehouseExists($validated["farm_id"]);
        if(! $userWarehouse)
            return $this->api(null,__METHOD__,'you dont have warehouse yet');


        $userFarm = $this->hasUserFarm($validated["farm_id"]);
        if(! $userFarm)
            return $this->api(null,__METHOD__,'you have done this before for this farm or dont have this farm yet');

        $userReferralReward = $this->getUserReferralReward($validated["farm_id"]);
        if(! $userReferralReward)
            return $this->api(null,__METHOD__,'operation failed,there is  no suitble reward referal for this farm ');

   

     





       $genOne =  $this->findParentReferral(67)?:null;
        $check = $this->isGenerationExists($genOne);
        if($check){
            $genOne =  $this->findParentReferral(67)?:null;
            $check = $this->isGenerationExists($genOne);


        }else{

            return $this->api(null.__METHOD__,'operation done');

        }



        dd($check); 
        

        /**
         * 0=>1
         * 1=>2
         * 2=>3
         * 3=>4
         */



    }
  

    public function isGenerationExists($model)
    {
        if($model)
            return true;

        return false;    
    }

    public function getUserReferralReward($farmId)
    {
        return UserReferralReward::query()
        ->where("farm_id",$farmId)
        ->first();
    }
    public function findParentReferral(int $inventedId)
    {
         return   UserReferral::query()
         ->where('invented_user',$inventedId)
         ->first()?:null;
    }


    public function hasUserFarm(int $farmId): bool
    {
        return UserFarms::query()
        ->where('user_id',auth()->id())
        ->where('farm_id',$farmId)
        ->where('reward','not_paied')
        ->exists();
    }


    public function isWarehouseExists($farmId): bool
    {
        return Wherehouse::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id())
        ->exists();
    }


}
