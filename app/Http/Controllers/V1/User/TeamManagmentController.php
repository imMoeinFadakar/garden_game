<?php

namespace App\Http\Controllers\V1\User;

use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserReferralResource;

class TeamManagmentController extends Controller
{
   
    public function index()
    {
       
        $user = User::find(auth()->id());

        $invites = $user->getInvitesWithIndirect(); 

        
       return $this->api(UserReferralResource::collection($invites->toArray()),__METHOD__);

    }



}
