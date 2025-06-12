<?php 


namespace   App\Services;

use App\Models\UserReferral;
use App\Models\TeammateRequest;
use Illuminate\Support\Facades\Cache;


class TeammateRequestService
{
    /**
     * @return int
     */
    public function getAllRequestCount(): int
    {
            return TeammateRequest::query()
            ->where('user_id',auth()->id())
            ->where('status' , 'pending')
            ->count();
    }

    /**
     * @return bool
     */
    public function  hasUserEnoughRefferal(): bool
    {
      
        $allReferral = $this->getUserTeammatesCount();

        if($allReferral >= 5 )
            return true;


        return false;
    }

    /**
     * @return int
     */
    protected function getUserTeammatesCount()
    {
         $b =  $this->getAllRequestCount() ;
        $a = $this->getUserRefferalCount();
        return $a + $b;
    }



    /**
     * @return int
     */
    protected function getUserRefferalCount(): int
    {
       return UserReferral::query()
       ->where('invading_user' , auth()->id())
       ->count(); 
    }

    /**
     * @return bool
     */
    public function hasUserEnoughGem(): bool
    {
        if(auth()->user()->gem_amount < 3)
            return false;

        return true;
    }

    /**
     * @return int
     */
    public function minuseUserGem(): int
    {
        return  auth()->user()->decrement('gem_amount',3);
    }


    





}