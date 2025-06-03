<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Resources\V1\User\TeamManagmentResource;
use App\Services\TeamManagmentService;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserReferralResource;

class TeamManagmentController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(TeamManagmentService $teamManagmentService)
    {
        $this->teamManagmentService = $teamManagmentService;
    }
    protected TeamManagmentService $teamManagmentService;


    public function getAllUserReferralQuentity()
    {
        
       $user = auth()->user();

        $invites = $user->getTopReferralTreeCountsWithFarmOwners(); 

       return $this->api($invites, __METHOD__);

    }



}
