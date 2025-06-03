<?php 

namespace App\Services;

use App\Models\UserReferral;


class TeamManagmentService{


    public function getGeneration(array $firstGeneration)
    {
        foreach($firstGeneration as $user){
            return  UserReferral::query()
            ->where('invading_user',$user)
            ->pluck('invented_user');   
        }
    }

    public function findUserReferralGeneration()
    {
        $user = auth()->user();

        $invate = $user->getInvitesWithIndirectCounts();
        

        

    }



}

