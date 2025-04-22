<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\UserReffralResource;
use App\Http\Resources\V1\User\UserReferralResource;
use App\Models\UserReferral;
use Illuminate\Http\Request;

class UserReferralController extends BaseUserController
{
    public function firstGenerationReffral()
    {
       
        $womens = $this->findFirstReferral(0); // 0 = male
        $male = $this->findFirstReferral(1); // 1 = female


            $result =
            [
                "male" => $male,
                "female" => $womens
            ];
         

        return $this->api(new UserReferralResource($result),__METHOD__,'user first generation referral');

    }

  




    public function findFirstReferral(int $gender): int
    {
        return  UserReferral::query()
        ->where("invading_user",auth()->id())
        ->where("gender",$gender)
        ->count();


    }

    public function findSecondReferral( )
    {
        
    }

  

}
