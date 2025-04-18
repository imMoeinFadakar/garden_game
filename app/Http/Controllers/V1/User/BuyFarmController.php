<?php

namespace App\Http\Controllers\V1\User;

use App\Models\Farms;
use App\Models\Products;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\UserReferralReward;
use App\Models\Wallet;
use App\Models\WarehouseLevel;
use App\Models\WarehouseProducts;
use App\Models\Wherehouse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\V1\User\BuyFarmResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest;

class BuyFarmController extends BaseUserController
{
    protected  $warehouse;

    public function __construct()
    {
        $this->warehouse = new Wherehouse();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuyfarmRequest $request,UserFarms $userFarms)
    {

        // get:user referral , farm , user Wallet
        $userWallet = $this->findWallet(Auth::id()); // find or make a new wallet
        $farm = $this->getFarm($request->farm_id);
        $userReferral = $this->userReferralNum(); // find count user referral

        // check user have enough resource
         $this->userHaveEnoughResource($userWallet->token_amount,$farm->require_token,"token");// has user enough token
         $this->userHaveEnoughResource($userWallet->gem_amount,$farm->require_gem,"gem"); // has user enough gem
         $this->userHaveEnoughResource($userReferral,$farm->require_referral,"referral"); // has user enough referral

        // minuse user resource from its wallet
        $newUserToken = $this->minuseUserResource($userWallet->token_amount,$farm->require_token);
        $newUserGem = $this->minuseUserResource($userWallet->gem_amount,$farm->require_gem);

        // add new resource amount in user wallet
        $this->insertNewUservalues($newUserGem,$newUserToken);


        $userFarmRequest = $request->validated();
        $userFarmRequest["user_id"] = Auth::id();
        $userFarm = $userFarms->addNewUserFarms($userFarmRequest);


        $product = $this->findProduct($farm->id); // find product by farmId
        $firstLevel = $this->firstWarehouseLevel($product->id); // fnd lvl1 warehouse for this new farm
        $this->createNewWarehouse($product->id,$firstLevel); // create new warehouse for selected farm

        $referralGenOne = $this->findUserReferral(1); // auth::id
        $referralGenTwo = $this->findUserReferral($referralGenOne);
        $referralGenThree = $this->findUserReferral($referralGenTwo); // auth::id
        $referralGenFour = $this->findUserReferral($referralGenThree); // auth::id

        $ReferralReward = $this->findReferralReward($farm->id);

        $userGenWarehouseOne = $this->findUserWarehouse($referralGenOne,$product->id);
        $userGenWarehouseTwo = $this->findUserWarehouse($referralGenTwo,$product->id);
        $userGenWarehouseThree = $this->findUserWarehouse($referralGenThree,$product->id);
        $userGenWarehouseFour = $this->findUserWarehouse($referralGenFour,$product->id);

        $fistReward =  $ReferralReward->reward_for_generation_one;
        $secondReward =  $ReferralReward->reward_for_generation_one;
        $threeReward =  $ReferralReward->reward_for_generation_one;
        $fourReward =  $ReferralReward->reward_for_generation_one;

        $this->payUserReward($userGenWarehouseOne->id,$product->id,$fistReward);
        $this->payUserReward($userGenWarehouseTwo->id,$product->id,$secondReward);
        $this->payUserReward($userGenWarehouseThree->id,$product->id,$threeReward);
        $this->payUserReward($userGenWarehouseFour->id,$product->id,$fourReward);



        return $this->api(new BuyFarmResource($userFarm->toArray()),__METHOD__);
    }

    public function payUserReward(int $userWarehouseId,int $productId,int $amount)
    {
        $warehouseProduct = WarehouseProducts::query()
            ->where("warehouse_id",$userWarehouseId)
            ->where("product_id",$productId)
            ->firstOrNew();

        $warehouseProduct->amount += $amount;
        return $warehouseProduct->save();
    }



    public function findUserWarehouse($userId,$productId)
    {
        $userWarehouse =  Wherehouse::query()
            ->where("user_id",$userId)
            ->where("product_id",)
            ->first();

        if($userWarehouse)
            return $userWarehouse->id;

        return null;
    }




    public function findUserReferral($userId)
    {
        return  UserReferral::query()->where("invented_user",$userId) // add auth
        ->first()->invading_user ?: null;
    }

    public function findReferralReward($farmId)
    {
        return UserReferralReward::query()
            ->where("farm_id",$farmId)
            ->first();
    }


    public function createNewWarehouse($productId,$firstLevel)
    {
        $credential = [
             "user_id" => Auth::id(),
             "product_id" => $productId,
             "warehouse_level_id" => $firstLevel->id,
             "warehouse_cap_left" => $firstLevel->max_cap_left,
             "overcapacity" => $firstLevel->overcapacity
        ];


        return Wherehouse::query()->create($credential);


    }


    public function firstWarehouseLevel(int $productId)
    {
        return WarehouseLevel::query()
            ->where("product_id",$productId)
            ->where("level_number",1)
            ->first();
    }

    public function findProduct($farmId)
    {
        return Products::query()->where("farm_id",$farmId)->first();
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

    public function userReferralNum(): int
    {
        return UserReferral::query()->where("invading_user",Auth::id())->count(); // add Auth::id
    }

    public function userHaveEnoughResource(int $UserResource,int $farmRequireResource,string $resourceName): bool
    {
        if($UserResource < $farmRequireResource)
            throw new HttpResponseException(response()->json([
                "success" => false,
                "message" => "you dont have enough $resourceName",
                "data" => null
            ]));


        return true;
    }


    public function getFarm($farmId)
    {
        $farm =  Farms::query()->find($farmId);
        if(! $farm)
            return $this->errorResponse(403,"farm does not exists");

        return $farm;
    }


    public function findWallet($userId): Wallet|null
    {
         return    Wallet::query()
            ->where("user_id",$userId) // add Auth::id()
            ->firstOrNew();

    }




}
