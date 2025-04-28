<?php

namespace App\Http\Controllers\V1\User;


use App\Models\Farms;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\V1\User\BuyFarmResource;
use App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest;

class BuyFarmController extends BaseUserController
{
  
 
    /**
     * buy new farm by user
     * @param \App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest $request
     * @param \App\Models\UserFarms $userFarms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(BuyfarmRequest $request,UserFarms $userFarms)
    {
        // get:user referral , farm , user Wallet
        $user = $this->findUser(auth()->id()); // find or 
        $farm = $this->getFarm($request->farm_id);
        
        $userFarm = $this->userFarmExists($farm->id,$user->id);
        if($userFarm)
            return $this->api(null,__METHOD__,"you already have this farm ");
    

    $userReferral = $this->userReferralNum(); // find count user referral
    
    // check user have enough resource
    $userToken =  $this->userHaveEnoughResource($user->token_amount,$farm->require_token);// has user enough token
    $userGem = $this->userHaveEnoughResource($user->gem_amount,$farm->require_gem); // has user enough gem
     $userReffralAmount = $this->userHaveEnoughResource($userReferral,$farm->require_referral); // has user enough referral
     
     if(! $userReffralAmount || ! $userGem || ! $userToken)
        return $this->api(null,__METHOD__,"you dont have enough resource to buy this farm");

    
    
    // minuse user resource from its wallet
    $newUserToken = $this->minuseUserResource($user->token_amount,$farm->require_token);
    $newUserGem = $this->minuseUserResource($user->gem_amount,$farm->require_gem);
    
    // add new resource amount in user 
    $this->insertNewUservalues($newUserGem,$newUserToken);

    $userFarmRequest = $request->validated();
    $userFarmRequest["user_id"] = auth()->id();
    $userFarmRequest["farm_power"] = $farm->power  ;
    $userFarm = $userFarms->addNewUserFarms($userFarmRequest);
    
       


        $newfarm  =[
            "farm_power" => $userFarm->farm_power,
            "reward" =>$userFarm->farm_power,
            "farm_id" => $userFarm->farm_id,
            'created_at' => $userFarm->created_at
        ];


        return $this->api(new BuyFarmResource($newfarm),__METHOD__);
    }

 

    /**
     * looking for farms that user own
     * @param int $farmId
     * @param int $userId
     * @return bool
     */
    public function userFarmExists(int $farmId,int $userId): bool
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
    public function insertNewUservalues(int $gem,int $token): int
    {
       return User::insertNewUserValue($gem,$token);
    }

    /**
     * @param int $userResource
     * @param int $farmResource
     * @return int
     */
    public function minuseUserResource(int $userResource,int $farmResource): int
    {
       return $userResource - $farmResource;
    }

    /**
     * return user referral number
     * @return int
     */
    public function userReferralNum(): int
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

    /**
     * Summary of getFarm
     * @param mixed $farmId
     * @return Collection<int, Farms>|mixed|\Illuminate\Http\JsonResponse
     */
    public function getFarm($farmId)
    {
        $farm = Farms::findFarm($farmId);
        if(! $farm)
            return $this->errorResponse("farm does not exists",403);

        return $farm;
    }

    /**
     * find user
     * @param mixed $userId
     * @return Collection<int, User>
     */
    public function findUser($userId)
    {
        return  User::find($userId);
    }




}
