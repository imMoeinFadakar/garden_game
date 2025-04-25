<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\payRewardResource;
use App\Models\Farms;
use App\Models\temporaryReward;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\UserReferralReward;
use App\Models\Wherehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\PayRewardRequest;

class PayRequestControler extends Controller
{   
   
    public function newPayingRequest(PayRewardRequest $request) 
    {
        $user = auth()->user(); // user
      
        if($user->warehouse_status === "inactive") // is warehouse active?
            return $this->api(null,__METHOD__,'you have to active your Warehouse');
      

        $validated = $request->validated(); 

        $userWarehouse = $this->isWarehouseExists($validated["farm_id"]); // is warehouse exists
        if(! $userWarehouse)
            return $this->api(null,__METHOD__,'you dont have warehouse yet');


        $userFarm = $this->hasUserFarm($validated["farm_id"]); 
        if(! $userFarm)
            return $this->api(null,__METHOD__,'you have done this before for this farm or dont have this farm yet');

        $userReferralReward = $this->getUserReferralReward($validated["farm_id"]); // find refferal reward 
        if(! $userReferralReward)
            return $this->api(null,__METHOD__,'operation failed,there is  no suitble reward referal for this farm ');

        $paymentFarmStatus = $this->userFarmRewardStatusPaying($userFarm); // is user payed before
        if(! $paymentFarmStatus)
            return $this->api(null,__METHOD__,'reward status change operation failed');

        $genOne = $this->findParentReferral(auth()->id()); // find user parent
        if($genOne){
            // gen one 
            $addUserReward = $this->addNewReward($userReferralReward->reward_for_generation_one,$genOne,$validated["farm_id"]);
          
            if($addUserReward){

                $genTwo = $this->findParentReferral($genOne->invading_user); // find gen two
                if($genTwo){
                    // gen two 
                    $addGenTwoReward = $this->addNewReward($userReferralReward->reward_for_generation_two,$genTwo,$validated["farm_id"]);
                   if($addGenTwoReward){

                    $genThree = $this->findParentReferral($genTwo->invading_user); //find gen three
                    if($genThree){
                        // gen three 
                        $addGenThreeReward = $this->addNewReward($userReferralReward->reward_for_generation_three,$genThree,$validated["farm_id"]);
                       
                        if($addGenThreeReward){

                            $genfour = $this->findParentReferral($genThree->invading_user); // find gen four
                            if($genfour){
                                // gen four 
                                $addGenfourReward = $this->addNewReward($userReferralReward->reward_for_generation_four,$genfour,$validated["farm_id"]);
                                if($addGenfourReward)
                                    return $this->api(null,__METHOD__,'reward add for 1,2,3,4');


                            }else{
                                return $this->api(null,__METHOD__,'gen 1,2,3');


                            }
                        }




                    }else{

                        return $this->api(null,__METHOD__,'gen 1,2');

                    }


                   }

                }else{

                    return $this->api(null,__METHOD__,'gen 1 added ');


                }


            }

        }else{
            return $this->api(null,__METHOD__,'referal parent not found');
            
        }
        

    }


    /**
     * Summary of userFarmRewardStatusPaying
     * @param mixed $userFarm
     */
    public function userFarmRewardStatusPaying($userFarm){
        $userFarm->reward = "paied";
        return $userFarm->save();
    }

    /**
     * Summary of addNewReward
     * @param int $amount
     * @param mixed $invetedUser
     * @param mixed $farmId
     * @return bool|temporaryReward
     */
    public function addNewReward(int $amount,$invetedUser,$farmId)
    {
     
        $userExists = $this->isGenerationExists($invetedUser);
        if($userExists){

            $exTime = Carbon::now()->addHours(12);

            
           return  temporaryReward::query()
            ->create([
                "user_id" => $invetedUser->invading_user,
                "farm_id" => intval($farmId),
                "amount" => $amount,
                "ex_time" => $exTime
            ]);
           

        }

        return false;
    }



    /**
     * check that is parent exists in referrals 
     * @param mixed $model
     * @return bool
     */
    public function isGenerationExists($model): bool
    {
        if($model)
            return true;

        return false;    
    }

    /**
     * find reward for referral s in this farm
     * @param mixed $farmId
     * @return UserReferralReward|null
     */
    public function getUserReferralReward($farmId): UserReferralReward|null
    {
        return UserReferralReward::query()
        ->where("farm_id",$farmId)
        ->first();
    }
    /**
     * find parent referral 
     * @param int $inventedId
     * @return UserReferral|null
     */
    public function findParentReferral(int $inventedId): UserReferral|null
    {
         return   UserReferral::query()
         ->where('invented_user',$inventedId)
         ->first()?:null;
    }

    /**
     * find user farm 
     * @param int $farmId
     * @return UserFarms|null
     */
    public function hasUserFarm(int $farmId): UserFarms|null
    {
        return UserFarms::query()
        ->where('user_id',auth()->id())
        ->where('farm_id',$farmId)
        ->where('reward','not_paied')
        ->first();
    }

    /**
     * @param int  $farmId
     * @return bool
     */
    public function isWarehouseExists(int $farmId): bool
    {
        return Wherehouse::query()
        ->where("farm_id",$farmId)
        ->where("user_id",auth()->id())
        ->exists();
    }


}
