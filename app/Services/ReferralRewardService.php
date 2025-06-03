<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\UserFarms;
use App\Models\Wherehouse;
use App\Models\UserReferral;
use App\Models\temporaryReward;
use App\Models\UserReferralReward;


class ReferralRewardService {

    public function handleReferralReward(int $userId , int $farmId)
    {
        
         if (! $this->isWarehouseExists($userId, $farmId)) {
            return 'You do not have a warehouse for this farm.';
        }

        $userFarm = $this->getUserFarm($userId,$farmId);

        if (! $userFarm) {
            return 'You have already requested reward for this farm or do not own it.';
        }

        $reward = $this->getReferralReward($farmId);

        if (! $reward) {
            return 'No referral reward found for this farm.';
        }

        $this->markRewardAsPaied($userFarm);

        return $this->distributeRewards($userId,$reward,$farmId);

    }



 
    protected function distributeRewards(int $userId, $reward, int $farmId)
    {
        $rewards = [
            $reward->reward_for_generation_one,
            $reward->reward_for_generation_two,
            $reward->reward_for_generation_three,
            $reward->reward_for_generation_four,
        ];

        $currentUserId = $userId;
        $genAdded = 0;

        foreach($rewards as $amount){

            $referral = $this->getParentReferral($currentUserId);

            if(! $referral) break ;

            $this->createReward($amount,$referral->invading_user,$farmId);

            $genAdded++;

            $currentUserId = $referral->invading_user;
        }

        return match ($genAdded){
            4 => 'Rewards added  for generation 1 to 4',
            3 => 'Rewards added  for generation 1 to 3',
            2 => 'Reward added  for generation 1 ,2',
            1 => 'Reward only added for generation 1',
            default => "no referal parent found"
        };

    }


    /**
     * @param int $userId
     * @param int $farmId
     * @param int $amount
     * @return temporaryReward
     */
    protected function createReward(int $amount, int $userId, int $farmId): temporaryReward
    {
        return temporaryReward::query()
        ->create([
            'user_id' => $userId,
            'farm_id' => $farmId,
            'amount' => $amount,
            'ex_time' => Carbon::now()->addHours(24),
        ]);
    }


    protected function markRewardAsPaied($userFarms): void
    {
        $userFarms->update(['reward' => 'paied']);
    }

    /**
     * @param int $farmId
     * @return UserReferralReward|null
     */
    protected function getReferralReward(int $farmId): ?UserReferralReward
    {
        return UserReferralReward::query()
        ->where('farm_id',$farmId)
        ->first();
    }

    /**
     * @param int $userId
     * @param int $farmId
     * @return UserFarms|null
     */
    protected function getUserFarm(int $userId , int $farmId): ?UserFarms
    {
        return UserFarms::query()
        ->where('user_id' , $userId)
        ->where('farm_id' , $farmId)
        ->where('reward' , 'not_paied')   
        ->first();
    }

    /**
     * @param int $userId
     * @param int $farmId
     * @return bool
     */
    protected function isWarehouseExists(int $userId ,int $farmId): bool
    {
        return Wherehouse::query()
        ->where('user_id',$userId)
        ->where( 'farm_id' ,  $farmId)
        ->exists();
    }


    /**
     * @param int $userId
     * @return UserReferral|null
     */
    protected function getParentReferral(int $userId): ?UserReferral
    {
         return UserReferral::query()
         ->where('invented_user', $userId)
         ->first();
    }
}
