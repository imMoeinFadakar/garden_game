<?php

namespace App\Http\Controllers\V1\User;

use App\Models\Farms;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\V1\User\BuyFarmResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest;

class BuyFarmController extends BaseUserController
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuyfarmRequest $request,UserFarms $userFarms)
    {

        // get:user referral  farm , user Wallet
        $userWallet = $this->userWallet();
        $farm = $this->getFarm($request->farm_id);
        $userReferral = $this->HasUserEnoughReferral();

        // check user have enough resource
        $userTokenStatus = $this->userHaveEnoughResource($userWallet->token_amount,$farm->require_token);// has user enough token
        $userGemStatus = $this->userHaveEnoughResource($userWallet->gem_amount,$farm->require_gem); // has user enough gem
        $userRefferalStatus = $this->userHaveEnoughResource($userReferral,$farm->require_referral); // has user enough gem

        // if user not have enough:
        if(! $userTokenStatus || ! $userGemStatus || ! $userRefferalStatus)
            return $this->api(
                    new BuyFarmResource([
                    "Farm token Require"=>$farm->require_token,
                    "Farm gem Require" => $farm->require_gem,
                    "Farm referral Require" => $farm->require_referral
                        ]),
                    false,
                    "Dont have enough resource");

        $newUserToken = $this->minuseUserResource($userWallet->token_amount,$farm->require_token);
        $newUserGem = $this->minuseUserResource($userWallet->gem_amount,$farm->require_gem);


        $this->insertNewUservalues($newUserGem,$newUserToken);


        $userFarmRequest = $request->validated();
        $userFarmRequest["user_id"] = Auth::id();
        $userFarm = $userFarms->addNewUserFarms($userFarmRequest);

        return $this->api(new BuyFarmResource($userFarm->toArray()),__METHOD__);
    }

    public function insertNewUservalues($gem,$token)
    {
        $userWallet = Wallet::query()->where("user_id",Auth::id())->first(); // add auth::id()
        $userWallet->token_amount = $token;
        $userWallet->gem_amount = $gem;
       return  $userWallet->save();
    }

    public function minuseUserResource(int $userResource,int $farmResource): int
    {
       return $userResource - $farmResource;
    }

    public function HasUserEnoughReferral()
    {
        return UserReferral::query()->where("invading_user",Auth::id())->count(); // add Auth::id
    }

    public function userHaveEnoughResource(int $UserResource,int $farmRequireResource): bool
    {
        if($UserResource < $farmRequireResource)
            return false;


        return true;
    }


    public function getFarm($farmId)
    {
        return Farms::query()->find($farmId);
    }


    public function userWallet(): Wallet|null
    {
         return  Wallet::query()
            ->where("user_id",Auth::id()) // add Auth::id()
            ->first();


    }




}
