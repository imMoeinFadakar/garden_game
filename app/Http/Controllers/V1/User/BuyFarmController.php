<?php

namespace App\Http\Controllers\V1\User;


use App\Models\User;
use App\Models\Farms;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Trait\UserActiveTrait;
use Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\V1\User\BuyFarmResource;
use App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest;
use Throwable;

class BuyFarmController extends BaseUserController
{
    use UserActiveTrait;
  
 
    /**
     * buy new farm by user
     * @param \App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest $request
     * @param \App\Models\UserFarms $userFarms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function buyFarmByUser(BuyfarmRequest $request,UserFarms $userFarms)
    {

        $user = auth()->user();
        $farm = $this->findFarm($request->farm_id);

        if(! $farm)
            return $this->api(null,
        __METHOD__,
        "farm does not exists",
        409);


        $hasUserFarm = $this->hadUserThisFarmBefore($farm->id,$user->id);

        if($hasUserFarm)
            return $this->api(null,
        __METHOD__,
        "you already have this farm ");
    

        $countUserReferral = $this->userReferralAmount(); // find count user referral
    
        DB::beginTransaction();
        try{

             if($user->token_amount < $farm->require_token ||
                $user->gem_amount < $farm->require_gem ||   
                $countUserReferral < $farm->require_referral)
                {
                    return $this->api(null, __METHOD__, "you dont have enough resource to buy this farm", 422);
                }       

                $user->update([
                'token_amount' => $user->token_amount - $farm->require_token,
                'gem_amount' => $user->gem_amount - $farm->require_gem,
                ]);

                $newUserFarm = $userFarms->addNewUserFarms([
                     'user_id' => $user->id,
                    'farm_id' => $farm->id,
                    'farm_power' => $farm->power,
                ]);

                 DB::commit();
                 $cacheKey = "user_farms_" . auth()->id();
                Cache::forget($cacheKey);

                return $this->api(new BuyFarmResource($newUserFarm), __METHOD__);

        }catch(Throwable $e){

             \DB::rollBack();
            return $this->api(null, __METHOD__, "Failed to buy farm", 500);

        }

    }

 

    /**
     * looking for farms that user own
     * @param int $farmId
     * @param int $userId
     * @return bool
     */
    public function hadUserThisFarmBefore(int $farmId,int $userId): bool
    {
        return UserFarms::query()
        ->where("user_id",$userId)
        ->where("farm_id",$farmId)
        ->exists();
    }

    /**
     * @param int $gem
     * @param int $token
     * @return int
     */
    public function updateUserResource(int $gem,int $token): int
    {
        return  auth()->user()->update([
            "token_amount" => $token,
            "gem_amount" => $gem
        ]);
    }

    /**
     * @param int $userResource
     * @param int $farmResource
     * @return int
     */
    public function deductUserResource(int $userResource,int $farmResource): int
    {
       return $userResource - $farmResource;
    }

    /**
     * return user referral number
     * @return int
     */
    public function userReferralAmount(): int
    {
        return UserReferral::userReferralNum(); 
    }
    /**
     * @param int $UserResource
     * @param int $farmRequireResource
     * @return bool
     */
    public function userHaveEnoughResource(int $UserResource,int $farmRequireResource): bool
    {       

        if($UserResource < $farmRequireResource)
            return false;


        return true;
    }

  
    public function findFarm(int $farmId)
    {
        return  Farms::find($farmId)??null;
    }


}
