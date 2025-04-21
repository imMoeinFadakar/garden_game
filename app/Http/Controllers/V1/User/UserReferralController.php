<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\UserReffralResource;
use App\Http\Resources\V1\User\UserReferralResource;
use App\Models\UserReferral;
use Illuminate\Http\Request;

class UserReferralController extends BaseUserController
{
    public function index()
    {
        $mensFirstGen = $this->findGenOneGender(1)->count(); // find men in user referral 
        $womenFirstGen = $this->findGenOneGender(0)->count();  

        

            $result =
            [
                "male" => $mensFirstGen,
                "female" => $womenFirstGen
            ];
         

        return $this->api($result,__METHOD__,'user first generation gender');

    }

    public function findSecondGen($firstGen)
    {
        
    }


    public function findGenOneGender($gender)
    {   
        
        return UserReferral::query()
        ->where("invading_user",1)
        ->where("gender",$gender)
        ->get('id');
    }

}
