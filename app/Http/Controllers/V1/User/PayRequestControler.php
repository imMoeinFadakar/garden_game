<?php

namespace App\Http\Controllers\V1\User;

use App\Models\temporaryReward;
use App\Http\Controllers\Controller;
use App\Services\ReferralRewardService;
use App\Http\Requests\V1\User\PayRewardRequest;
use App\Http\Resources\V1\User\payRewardResource;
use Illuminate\Support\Facades\Cache;



class PayRequestControler extends Controller
{   
    private ReferralRewardService $referralRewardService;

    public function __construct(ReferralRewardService $referralRewardService)
    {
        $this->referralRewardService = $referralRewardService;
    }

    public function getUserReferralReward()
    {
        $rewards = TemporaryReward::query()
        ->where('user_id', auth()->id())
        ->get(['id', 'farm_id', 'amount', 'ex_time', 'created_at']);

        return $this->api(PayRewardResource::collection($rewards), __METHOD__);
    }


    public function newPayingRequest(PayRewardRequest $request)
    {
        $user = auth()->user();

        if($user->warehouse_status  === "inactive")
            return $this->api(null,__METHOD__,'you must active your warehouse first');
        

        $message = $this->referralRewardService->handleReferralReward($user->id, $request->farm_id);

        return $this->api(null,__METHOD__,$message);

    }




}
