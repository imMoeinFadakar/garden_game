<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserRefrral\StoreUserRefrralRequest;
use App\Http\Resources\V1\User\UserReferralResource;
use App\Models\Farms;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReferralController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        // gen 1 refer
//        $generationOne = $this->findUserRefrral();
//        $allReferralIdsOne = $this->getUserIdInModels($generationOne);
//        $findUserReferrals = $this->findUserReferrals($allReferralIdsOne,1);
//        dd($findUserReferrals->count());



//        // gen 2 refer
//        $genTwoReff = $this->findGenerationReferral($allReferralIdsOne);
//        $allReferralIdsTwo = $this->getUserIdInModels($genTwoReff);
//
//        // gen 3 refer
//        $genThreeReff = $this->findGenerationReferral($allReferralIdsTwo);
//        $allReferralIdsThree = $this->getUserIdInModels($genThreeReff);
//
//        // gen 4 refer
//        $genThreeReff = $this->findGenerationReferral($allReferralIdsThree);
//        $allReferralIdsFour = $this->getUserIdInModels($genThreeReff);



//    }

//    public function findUserReferrals(array $userIds): \Illuminate\Database\Eloquent\Builder
//    {
//        $allFarms = [];
//
//        $allFarms = $this->allCountFarms();
//        foreach ($allFarms as $farm)
//            $allFarms[$farm->name] = UserFarms::query()
//                ->whereIn("user_id", $userIds)
//                ->where("farm_id", $farm->id)
//                ->with(["user:id,username", "farm:name"])
//                ->get();
//        }
//
//        return
//    }

    public function allCountFarms()
    {
        return Farms::all();
    }


    public function findGenerationReferral(array $allReferralIds)
    {
        return  UserReferral::query()
            ->whereIn("invading_user",$allReferralIds)
            ->get();
    }
    public function getUserIdInModels($Models): array
    {
        $allReferredUsers = [];
        foreach($Models as $Model){

            $allReferredUsers[] = $Model->invented_user;

        }

        return $allReferredUsers;
    }



    public function findUserRefrral()
    {
        return UserReferral::query()
            ->orderBy("id")
            ->where("invading_user",1) // add Auth::id() later
            ->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRefrralRequest $request,UserReferral $userReferral)
    {
        $invading_user = $this->findUserId($request->Referral_code);
        $invented_user = Auth::id(); // the new user

        $newReferral = [
            "invading_user" => $invading_user,
            "invented_user" => $invented_user
        ];


      $userReferral =   $userReferral->addNewUserReferral($newReferral);
        return $this->api(new UserReferralResource($userReferral->toArray()),__METHOD__);

    }

    public function findUserId($referralCode)
    {
        return  Wallet::query()
            ->where("Referral_code",$referralCode)
            ->first()->user_id;
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
