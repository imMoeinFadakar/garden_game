<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\USerReffralReward\StoreUserReffralRewardRequest;
use App\Http\Requests\V1\Admin\USerReffralReward\UpdateUserReffralRewardRequest;
use App\Http\Resources\V1\Admin\UserReffralRewardResource;
use App\Models\UserReferralReward;
use Illuminate\Http\Request;

class UserReferralRewardController extends BaseAdminController
{   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userReffralReward = UserReferralReward::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->farm_id),fn($query)=> $query->where("farm_id", $request->farm_id))
        ->with(["farm:id,name"])
        ->get();

        return $this->api(UserReffralRewardResource::collection($userReffralReward),__METHOD__);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserReffralRewardRequest $request, UserReferralReward $userReferralReward)
    {
       $userReferralReward =  $userReferralReward->addNewUserReffralReward($request);
       $userReferralReward->load(["farm:id,name"]);
        return $this->api(new UserReffralRewardResource($userReferralReward->toArray()),__METHOD__);
    }

    public function show(UserReferralReward $userReferralReward)
    {
       $userReferralReward->load(["farm:id,name"]);
        return $this->api(new UserReffralRewardResource($userReferralReward->toArray()),__METHOD__);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserReffralRewardRequest $request, UserReferralReward $userReferralReward)
    {
        $userReferralReward->updateUserReffralReward($request);
       $userReferralReward->load(["farm:id,name"]);
        return $this->api(new UserReffralRewardResource($userReferralReward->toArray()),__METHOD__);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserReferralReward $userReferralReward)
    {
        $userReferralReward->deleteUserReffralReward();
        return $this->api(new UserReffralRewardResource($userReferralReward->toArray()),__METHOD__);

    }
}
