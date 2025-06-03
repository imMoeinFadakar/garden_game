<?php 

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserFarms;
use App\Models\UserReferral;
use App\Models\temporaryReward;
use App\Models\UserReferralReward;

class RewardService{

    public function processRewardPayment(User $user,int $farmId)
    {
        
        if($user->warehouse_status === 'inactive') {
            return ['success' => false, 'message' => 'you have to active your Warehouse'];
        }

        $userFarm = $this->getUserFarm($user->id,$farmId);
         if (! $userFarm) {
            return ['success' => false, 'message' => 'you have done this before for this farm or dont have this farm yet'];
        }

        $rewardConfig = $this->getRewardConfig($farmId);
        if (! $rewardConfig) {
            return ['success' => false, 'message' => 'no suitable referral reward for this farm'];
        }

        $userFarm->reward = 'paied';
        $userFarm->save();


        $result = [];
        $currentUserId = $user->id;
          $generations = [
            1 => $rewardConfig->reward_for_generation_one,
            2 => $rewardConfig->reward_for_generation_two,
            3 => $rewardConfig->reward_for_generation_three,
            4 => $rewardConfig->reward_for_generation_four,
        ];

        foreach($generations as $gen => $amount){

            $referral = $this->getParentReferral($currentUserId);
            if(! $referral) break;

            $this->createReward($referral->invading_user, $farmId, $amount);
            $results[] = "gen $gen added";
            $currentUserId = $referral->invading_user;
        }

        return ['success'=>true,'message'=>implode(',',$result)];

    }

    protected function getUserFarm(int $userId,int $farmId)
    {
        return UserFarms::query()
        ->where('user_id',$userId)
        ->where('farm_id',$farmId)
        ->where('reward', 'not_paied')
        ->first();
    }


    protected function getRewardConfig(int $farmId)
    {
        return UserReferralReward::query()
        ->where('farm_id',$farmId)
        ->first();
    }

    protected function getParentReferral(int $userId)
    {
         return UserReferral::query()
        ->where('farm_id',$userId)
        ->first();
    }

    protected function createReward(int $userId , int $farmId,int $amount)
    {
        temporaryReward::query()
        ->create([
               'user_id' => $userId,
            'farm_id' => $farmId,
            'amount' => $amount,
            'ex_time' => Carbon::now()->addHours(12),
        ]);
    }
}



