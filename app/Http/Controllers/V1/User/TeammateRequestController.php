<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Models\TeammateRequest;
use App\Services\TeamManagmentService;
use App\Services\TeammateRequestService;
use Exception;
use Illuminate\Http\Request;

class TeammateRequestController extends BaseUserController
{
    
    protected TeammateRequestService $teammateRequestService;
    protected UserController $userController;
    /**
     * Class constructor.
     */
    public function __construct(TeammateRequestService $teammateRequestService,UserController $userController)
    {
        $this->teammateRequestService = $teammateRequestService;
        $this->userController = $userController;
    }



    public function getAllTeammateRequestCount()
    {
        
        $teammateRequestCount = (int) $this->teammateRequestService->getAllRequestCount();

       return  $this->api(['request_number' => $teammateRequestCount],__METHOD__);

    }

    public function addNewTeammateRequest(Request $request,TeammateRequest $teammateRequest)
    {

        try{

            $hasUserTeammate = $this->teammateRequestService->hasUserEnoughRefferal();
            // dd($hasUserTeammate);


            if($hasUserTeammate)
                return $this->api(null,__METHOD__,"You cant add request becuse you have enough referral or request!");

            $userGem = $this->teammateRequestService->hasUserEnoughGem();
            if(! $userGem)
                return $this->api(null,__METHOD__,"You dont have enough gem");
    
            $this->teammateRequestService->minuseUserGem();
            
            $request = $teammateRequest->addNewTeammateRequest(['user_id' => auth()->id()]);

            $parentlessUser = $this->userController->findParentlessUser();
            if(! $parentlessUser)
            return $this->api(null,__METHOD__,"Request added successfuly");
        
            $this->userController->updateTeammateRequest($request);
        
            $this->userController->addNewTeammateForUser($parentlessUser,auth()->id());

            $parentlessUser->has_parent = "true";
            $parentlessUser->save();

            return $this->api(null,__METHOD__,"New teammate added to your team");

        }catch(Exception $e){

            return $this->api(null,__METHOD__,"operation failed! " .
             $e->getMessage() . " ," . $e->getLine() . " , " ); 

        }


    }










}
